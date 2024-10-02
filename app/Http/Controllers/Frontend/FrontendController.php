<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\WhyChooseUs;
use App\Models\SectionTitle;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Psy\Command\WhereamiCommand;

class FrontendController extends Controller
{
    public function index()
    {
        $sectionTitles = $this->getSectionTitles();
        $sliders = Slider::where('status', 1)->get();
        $whyChooseUs = WhyChooseUs::where('status', 1)->get();
        $categories = Category::where(['show_at_home' => 1, 'status' => 1])->get();
        return view('frontend.home.index', compact('sliders', 'whyChooseUs', 'sectionTitles', 'categories'));
    }

    public function showProduct($slug)
    {
        $product = Product::with(['galleries', 'sizes', 'options'])
            ->where(['slug' => $slug, 'status' => 1])->firstOrFail();
        $relatedProducts = Product::where(['category_id' => $product->category_id, 'status' => 1])
            ->where('id', '!=', $product->id)->take(8)->latest()->get();
        return view('frontend.pages.product-view', compact('product', 'relatedProducts'));
    }

    public function getSectionTitles(): Collection
    {
        $keys = [
            'why_choose_top_title',
            'why_choose_main_title',
            'why_choose_sub_title',
        ];

        return SectionTitle::whereIn('key', $keys)->pluck('value', 'key');
    }

    public function addToCartModal($slugId)
    {
        $product = Product::with(['sizes', 'options'])->where('id', $slugId)->findOrFail($slugId);

        return view('frontend.layouts.ajax-files.add-to-cart-modal', compact('product'))->render();
    }

    public function applyCoupon(Request $request)
    {
        $subtotal = $request->subtotal;
        $code = $request->code;

        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response(['message' => 'Invalid Coupon Code.'], 422);
        }

        if ($coupon->quantity <= 0) {
            return response(['message' => 'Coupon has been fully redeemed.'], 422);
        }

        if ($coupon->expire_date < now()) {
            return response(['message' => 'Coupon has expired.'], 422);
        }

        if ($coupon->discount_type === 'percent') {
            $discount = number_format($subtotal * ($coupon->discount / 100), 2);
        } elseif ($coupon->discount_type === 'amount') {
            $discount = number_format($coupon->discount, 2);
        }

        $discount = $coupon->discount_type === 'percent'
            ? number_format($subtotal * ($coupon->discount / 100), 2)
            : number_format($coupon->discount, 2);

        $finalTotal = $subtotal - $discount;

        session()->put('coupon', ['code' => $code, 'discount' => $discount]);

        return response([
            'message' => 'Coupon Applied Successfully.',
            'discount' => $discount,
            'finalTotal' => $finalTotal,
            'coupon_code' => $code,
        ]);
    }

    public function destroyCoupon()
    {
        try {
            session()->forget('coupon');
            return response(['message' => 'Coupon Removed!', 'cart_grand_total' => grandTotalCart()]);
        } catch (\Exception $e) {
            logger($e);
            return response(['message' => 'Something went wrong']);
        }
    }
}
