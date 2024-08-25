<?php

use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\WhyChooseUsController;
use App\Http\Controllers\Admin\CategoryController;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    /**Profile Routes */
    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    /**Slider Routes */
    Route::resource('slider', SliderController::class);

    /**Why choose us Routes */
    Route::put('why-choose-us-title-update', [WhyChooseUsController::class, 'updateTitle'])
        ->name('why-choose-us.title.update');
    Route::resource('why-choose-us', WhyChooseUsController::class);

    /**Product Category Routes */
    Route::resource('category', CategoryController::class);
});
