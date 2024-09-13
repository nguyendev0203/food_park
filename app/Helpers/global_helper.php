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
    /**
     * Calculates the total price of items in the cart.
     *
     * @return float The total price of the cart.
     */
    function cartTotal()
    {
        $total = 0;

        foreach (Cart::content() as $item) {
            $productPrice = $item->price;
            $sizePrice = isset($item->options['size']['price']) ? $item->options['size']['price'] : 0;
            $optionsPrice = array_sum(array_column($item->options['extra'], 'price'));

            $total += ($productPrice + $sizePrice + $optionsPrice) * $item->qty;
        }

        return $total;
    }
}
