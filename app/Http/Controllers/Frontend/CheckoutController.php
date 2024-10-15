<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\DeliveryArea;
use Auth;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $addresses = Address::where(['user_id' => Auth::user()->id])->get();
        $deliveryAreas = DeliveryArea::where('status', 1)->get();
        return view('frontend.pages.checkout', compact('addresses', 'deliveryAreas'));
    }

    public function CalculateShippingCost(string $addressId)
    {
        try {
            $address = Address::findOrFail($addressId);
            $shippingCost = $address->deliveryArea?->delivery_fee;
            $grandTotal = grandTotalCart($shippingCost);
            return response(['shipping_cost' => $shippingCost, 'grand_total' => $grandTotal]);
        } catch (\Exception $e) {
            logger($e);
            return response(['message' => 'Something Went Wrong!'], 422);
        }
    }

    public function checkoutRedirect(Request $request)
    {
        $request->validate([
            'addressId' => ['required', 'integer']
        ]);

        $address = Address::with('deliveryArea')->findOrFail($request->addressId);

        $selectedAddress = $address->address . ', Aria: ' . $address->deliveryArea?->area_name;

        session()->put('address', $selectedAddress);
        session()->put('shipping_cost', $address->deliveryArea->delivery_fee);
        session()->put('delivery_area_id', $address->deliveryArea->id);


        return response(['redirect_url' => route('payment.index')]);
    }
}
