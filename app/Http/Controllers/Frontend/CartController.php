<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        try {
            $product = Product::with(['sizes', 'options'])->findOrFail($request->product_id);
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
            return response(['status' => 'error', 'message' => 'Something went wrong!'], 500);
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
            return response([
                'status' => 'success',
                'message' => 'Product removed from cart!'
            ], 200);
        } catch (\Exception $e) {
            logger($e);
            return response([
                'status' => 'error',
                'message' => 'Something went wrong!'
            ], 500);
        }
    }
}
