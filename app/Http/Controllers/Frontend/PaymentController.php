<?php

namespace App\Http\Controllers\Frontend;

use App\Events\OrderPaymentUpdateEvent;
use App\Events\OrderPlacedNotificationEvent;
use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use Illuminate\View\View;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Razorpay\Api\Api as RazorpayApi;

class PaymentController extends Controller
{
    public const  method =
    [
        'paypal' => 'PayPal',
        'stripe' => 'Stripe',
        'razorpay' => 'Razorpay'
    ];
    /**
     * @return void
     */
    public function index()
    {
        if (!session()->has('shipping_cost') || !session()->has('address')) {
            throw ValidationValidationException::withMessages(['Something went wrong']);
        }

        $subtotal = cartTotal();
        $shippingCost = session()->get('shipping_cost') ?? 0;
        $discount = session()->get('coupon')['discount'] ?? 0;
        $grandTotal = grandTotalCart($shippingCost);

        return view('frontend.pages.payment', compact('subtotal', 'shippingCost', 'discount', 'grandTotal'));
    }

    public function successPayment(): View
    {
        return view('frontend.pages.success-payment');
    }

    public function cancelPayment(): View
    {
        return view('frontend.pages.cancel-payment');
    }

    public function makePayment(Request $request, OrderService $order)
    {
        $request->validate([
            'payment_gateway' => ['required', 'string', 'in:paypal,stripe,razorpay']
        ]);

        if ($order->createOrder()) {
            switch ($request->payment_gateway) {
                case 'paypal':
                    return response(['redirect_url' => route('paypal.payment')]);
                    break;
                case 'stripe':
                    return response(['redirect_url' => route('stripe.payment')]);
                    break;
                case 'razorpay':
                    return response(['redirect_url' => route('razorpay-redirect')]);
                    break;
                default:
                    break;
            }
        }
    }

    public function setPaypalConfig(): array
    {
        return [
            'mode' => config('gatewaySettings.paypal_account_mode'),
            'sandbox' => [
                'client_id' => config('gatewaySettings.paypal_api_key'),
                'client_secret' => config('gatewaySettings.paypal_secret_key'),
                'app_id' => 'APP-80W284485P519543T',
            ],
            'live' => [
                'client_id' => config('gatewaySettings.paypal_api_key'),
                'client_secret' => config('gatewaySettings.paypal_secret_key'),
                'app_id' => config('gatewaySettings.paypal_app_id'),
            ],
            'payment_action' => 'Sale',
            'currency' => config('gatewaySettings.paypal_currency'),
            'notify_url' => env('PAYPAL_NOTIFY_URL', ''),
            'locale' => 'en_US',
            'validate_ssl' => true,
        ];
    }

    public function payWithPaypal()
    {
        $config = $this->setPaypalConfig();
        $provider = new PayPalClient($config);
        $provider->getAccessToken();
        $grandTotal = session()->get('grand_total');
        $payableAmount = ($grandTotal * config('gatewaySettings.paypal_rate'));

        $response = $provider->createOrder([
            'intent' => "CAPTURE",
            'application_context' => [
                'return_url' => route('paypal.success'),
                'cancel_url' => route('paypal.cancel')
            ],
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => config('gatewaySettings.paypal_currency'),
                        'value' => $payableAmount
                    ]
                ]
            ]
        ]);

        if (!empty($response['id']) && isset($response['links'])) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        }

        return redirect()->route('payment.cancel')->withErrors(['error' => $response['error']]);
    }

    public function paypalSuccess(Request $request, OrderService $order)
    {
        $config = $this->setPaypalConfig();
        $provider = new PayPalClient($config);
        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request->token);

        if (
            isset($response['status'])
            && $response['status'] === 'COMPLETED'
        ) {
            $orderId = session()->get('order_id');
            $capture = $response['purchase_units'][0]['payments']['captures'][0];
            $paymentInfo = [
                'transaction_id' => $capture['id'],
                'currency' => $capture['amount']['currency_code'],
                'status' => $capture['status']
            ];

            OrderPaymentUpdateEvent::dispatch($orderId, $paymentInfo, self::method['paypal']);
            OrderPlacedNotificationEvent::dispatch($orderId);

            $order->clearSession();
            return redirect()->route('payment.success');
        }

        $this->transactionUpdateFail(self::method['paypal']);
        return redirect()->route('payment.cancel')->withErrors(['error' => $response['error']['message']]);
    }

    public function paypalCancel(Request $request)
    {
        return redirect()->route('payment.cancel');
    }

    public function payWithStripe()
    {
        Stripe::setApiKey(config('gatewaySettings.stripe_secret_key'));
        $grandTotal = session()->get('grand_total');
        $amount = round($grandTotal * config('gatewaySettings.stripe_rate'), 2) * 100;

        $response = StripeSession::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => config('gatewaySettings.stripe_currency'),
                        'product_data' => [
                            'name' => 'Product'
                        ],
                        'unit_amount' => $amount
                    ],
                    'quantity' => 1
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel')
        ]);

        return redirect()->away($response->url);
    }

    public function stripeSuccess(Request $request, OrderService $order)
    {
        $sessionId = $request->session_id;
        Stripe::setApiKey(config('gatewaySettings.stripe_secret_key'));

        $response = StripeSession::retrieve($sessionId);

        if ($response->payment_status === 'paid') {

            $orderId = session()->get('order_id');
            $paymentInfo = [
                'transaction_id' => $response->payment_intent,
                'currency' => $response->currency,
                'status' => 'completed'
            ];

            OrderPaymentUpdateEvent::dispatch($orderId, $paymentInfo, 'Stripe');
            OrderPlacedNotificationEvent::dispatch($orderId);

            $order->clearSession();
            return redirect()->route('payment.success');
        }

        $this->transactionUpdateFail('stripe');
        return redirect()->route('payment.cancel');
    }

    public function stripeCancel()
    {
        $this->transactionUpdateFail('stripe');
        return redirect()->route('payment.cancel');
    }

    public function razorpayRedirect()
    {
        return view('frontend.pages.razorpay-redirect');
    }

    public function payWithRazorpay(Request $request, OrderService $orderService)
    {
        $api = new RazorpayApi(
            config('gatewaySettings.razorpay_api_key'),
            config('gatewaySettings.razorpay_secret_key'),
        );

        if ($request->has('razorpay_payment_id') && $request->filled('razorpay_payment_id')) {
            $grandTotal = session()->get('grand_total');
            $amount = ($grandTotal * config('gatewaySettings.razorpay_rate')) * 100;

            try {
                $response = $api->payment
                    ->fetch($request->razorpay_payment_id)
                    ->capture(['amount' => $amount]);
                if ($response['status'] === 'captured') {
                    $orderId = session()->get('order_id');
                    $paymentInfo = [
                        'transaction_id' => $response->id,
                        'currency' => config('settings.site_default_currency'),
                        'status' => 'completed'
                    ];

                    OrderPaymentUpdateEvent::dispatch($orderId, $paymentInfo, 'Razorpay');
                    OrderPlacedNotificationEvent::dispatch($orderId);
                    $orderService->clearSession();

                    return redirect()->route('payment.success');
                }
                $this->transactionUpdateFail('Razorpay');
                return redirect()->route('payment.cancel')->withErrors(['error' => $response['error']]);
            } catch (\Exception $e) {
                logger($e);
                $this->transactionUpdateFail('Razorpay');
                return redirect()->route('payment.cancel')->withErrors($e->getMessage());
            }
        }
    }

    public function transactionUpdateFail($gatewayName): void
    {
        $orderId = session()->get('order_id');
        $paymentInfo = [
            'transaction_id' => '',
            'currency' => '',
            'status' => 'Failed'
        ];

        OrderPaymentUpdateEvent::dispatch($orderId, $paymentInfo, $gatewayName);
    }
}
