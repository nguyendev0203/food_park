<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function index()
    {
        return view('frontend.pages.cart-view');
    }

    public function addToCart(Request $request)
    {
        try {
            $product = Product::with(['sizes', 'options'])->findOrFail($request->product_id);

            if ($product->quantity < $request->quantity)
            {
                throw ValidationException::withMessages(['Quantity is not available!']);
            }

            $options = [
                'size' => [],
                'extra' => [],
                'info' => [
                    'image' => $product->thumb_image,
                    'slug' => $product->slug
                ]
            ];
            $size = $product->sizes()->where('id', $request->product_size)->first();
            if ($size !== null) {
                $options['size'] = [
                    'name' => $size?->name,
                    'price' => $size?->price,
                    'id' => $size?->id
                ];
            }

            $extra = collect();
            if ($request->has('product_option') && !empty($request->product_option)) {
                $extra = $product->options()->whereIn('id', $request->product_option)->get();
            }

            foreach ($extra as $item) {
                $options['extra'][] = [
                    'name' => $item->name,
                    'price' => $item->price,
                    'id' => $item->id
                ];
            }

            Cart::add([
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $request->quantity,
                'price' => $product->offer_price > 0 ? $product->offer_price : $product->price,
                'weight' => 0,
                'options' => $options
            ]);

            return response(['status' => 'success', 'message' => 'Product added into cart!'], 200);
        } catch (\Exception $e) {
            logger($e);
            return response(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getCartProduct()
    {
        return view('frontend.layouts.ajax-files.sidebar-cart-item')->render();
    }

    public function cartProductRemove(string $rowId)
    {
        try {
            Cart::remove($rowId);
            
            if(Cart::count() == 0){
                session()->forget('coupon');
            }
            return response([
                'status' => 'success',
                'message' => 'Product removed from cart!',
                'cart_total' => cartTotal(),
                'cart_grand_total' => grandTotalCart(),
                'cart_count' => Cart::count() 
            ], 200);
        } catch (\Exception $e) {
            logger($e);
            return response([
                'status' => 'error',
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function updateCartQuantity(Request $request)
    {
        $cartItem = Cart::get($request->rowId);
        $product = Product::findOrFail($cartItem->id);

        if($product->quantity < $request->qty){
            return response(['status' => 'error', 'message' => 'Quantity is not available!', 'qty' => $cartItem->qty]);
        }

        try {
            $cart = Cart::update($request->rowId, $request->qty);
            return response([
                'status' => 'success',
                'product_total' => productTotal($request->rowId),
                'qty' => $cart->qty,
                'cart_total' => cartTotal(),
                'cart_grand_total' => grandTotalCart()
            ], 200);

        } catch (\Exception $e) {
            logger($e);
            return response(['status' => 'error', 'message' => 'Something went wrong please reload the page.'], 500);
        }
    }

    public function cartDestroy()
    {
        Cart::destroy();
        session()->forget('coupon');
        return redirect()->back();
    }
}
