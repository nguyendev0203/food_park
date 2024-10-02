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

    function CalculateShippingCost(string $addressId)
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
}
