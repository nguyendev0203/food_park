<?php

/**
 * Create unique slug
 * 
 * @param string $model
 * @param string $name
 * @return string
 * @throws InvalidArgumentException If the model class does not exist.
 */

use Gloudemans\Shoppingcart\Facades\Cart;

if (!function_exists('generateUniqueSlug')) {
    function generateUniqueSlug(string $model, string $name): string
    {
        $modelClass = "App\\Models\\$model";

        if (!class_exists($modelClass)) {
            throw new \InvalidArgumentException("Model $model not found.");
        }

        $slug = Str::slug($name);
        $counter = 1;

        while ($modelClass::where('slug', $slug)->exists()) {
            $slug = \Str::slug($name) . '-' . ++$counter;
            $counter++;
        }

        return $slug;
    }
}

if (!function_exists('currencyPosition')) {
    function currencyPosition($price): string
    {
        if (config('settings.site_currency_icon_position') === 'left') {
            return config('settings.site_currency_icon') . $price;
        } else {
            return $price . config('settings.site_currency_icon');
        }
    }
}

/** Calculate cart total price */
if (!function_exists('cartTotal')) {
    function cartTotal()
    {
        $total = 0;

        foreach (Cart::content() as $item) {
            $total += ($item->price + ($item->options['size']['price'] ?? 0) +
                array_sum(array_column($item->options->extra, 'price'))) * $item->qty;
        }

        return $total = number_format($total, 2, '.', '');
    }
}

/** Calculate product total price */
if (!function_exists('productTotal')) {
    function productTotal($rowId)
    {
        $product = Cart::get($rowId);
        $total = ($product->price + ($product->options?->size['price'] ?? 0) +
            array_sum(array_column($product->options->extra, 'price'))) * $product->qty;

        return $total = number_format($total, 2, '.', '');;
    }
}

/** Grand cart total */
if (!function_exists('grandTotalCart')) {
    function grandTotalCart($deliveryFee = 0)
    {
        $total = cartTotal() + $deliveryFee;

        if (session()->has('coupon')) {
            $discount = session()->get('coupon')['discount'];
            $total -= $discount;
        }

        return $total = number_format($total, 2, '.', '');
    }
}

/** Generate Invoice Id */
if (!function_exists('generateInvoiceId')) {
    function generateInvoiceId()
    {
        $randomNumber = rand(1, 9999);
        $currentDateTime = now();

        $invoiceId = $randomNumber . $currentDateTime->format('yd') . $currentDateTime->format('s');

        return $invoiceId;
    }
}
