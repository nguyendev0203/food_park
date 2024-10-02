<?php

use App\Http\Controllers\Frontend\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\DashboardController;


/**
 * Admin Auth Routes
 */
Route::group(['middleware' => ['guest']], function () {
   Route::get('admin/login', [AdminAuthController::class, 'index'])->name('admin.login');
   Route::get('admin/forget-password', [AdminAuthController::class, 'forgetPassword'])->name('admin.forget-password');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
   Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
   Route::put('profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
   Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
   Route::post('profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
   Route::post('address', [DashboardController::class, 'createAddress'])->name('address.store');
   Route::put('address/{id}/edit', [DashboardController::class, 'updateAddress'])->name('address.update');
   Route::delete('address/{id}', [DashboardController::class, 'destroyAddress'])->name('address.destroy');
});

require __DIR__ . '/auth.php';

/** Home Page */
Route::get('/', [FrontendController::class, 'index'])->name('home');

/** Product details */
Route::get('/product/{slug}', [FrontendController::class, 'showProduct'])->name('product.show');

/** Add to cart modal*/
Route::get('/add-to-cart-modal/{slug}', [FrontendController::class, 'addToCartModal'])->name('add-to-cart-modal');

/** Add to cart */
Route::post('add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
Route::get('get-cart-products', [CartController::class, 'getCartProduct'])->name('get-cart-products');
Route::get('cart-product-remove/{rowId}', [CartController::class, 'cartProductRemove'])->name('cart-product-remove');

/** Cart Page Routes */
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart-quantity-update', [CartController::class, 'updateCartQuantity'])->name('cart.quantity-update');
Route::get('/cart-destroy', [CartController::class, 'cartDestroy'])->name('cart.destroy');

/** Coupon Routes */
Route::post('/apply-coupon', [FrontendController::class, 'applyCoupon'])->name('apply-coupon');
Route::get('/destroy-coupon', [FrontendController::class, 'destroyCoupon'])->name('destroy-coupon');

Route::group(['middleware' => 'auth'], function () {
   Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
   Route::get('checkout/{addressId}/shipping-cost',[CheckoutController::class,'CalculateShippingCost'])->name('checkout.shipping-cost');

});
