<?php

use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentStatusController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


// Testing
Route::group([
    'middleware' => 'checkAuth:default'
], function () {
    Route::get('/test-auth', [CartController::class, 'testAuth']);
    // Account Settings
    Route::get('/payment-midtrans', [PaymentController::class, 'createPayment']);

    // Home
    Route::get('/', [HomeController::class, 'view'])->name('home');
    // Products
    Route::get('/products', [ProductController::class, 'view'])->name('products');
    Route::get('/products/fetch', [ProductController::class, 'fetch']);
    Route::get('/products/detail', [ProductController::class, 'single'])->name('product.detail');
    // Cart
    Route::get('/cart',  [CartController::class, 'view'])->name('cart');
    Route::post('/cart/validate', [CartController::class, 'validate_cart'])->name('cart.validate');
    Route::post('/cart/apply-voucher', [CartController::class, 'apply_voucher'])->name('cart.apply-voucher');
    Route::post('/cart/download-draft',  [CartController::class, 'download_draft_cart'])->name('cart.download-draft');
    // Other
    Route::get('/about', function () {
        return Inertia::render('About');
    })->name('about');
    Route::get('/contact', function () {
        return Inertia::render('Contact');
    })->name('contact');
    Route::get('/gallery', function () {
        return Inertia::render('Gallery');
    })->name('gallery');
    Route::get('/contact', function () {
        return Inertia::render('Contact');
    })->name('contact');
    Route::get('/payment-status', [PaymentStatusController::class, 'view'])->name('payment');

    // Login
    Route::get('/login', [AuthenticationController::class, 'view'])->name('login');
    Route::post('/login', [AuthenticationController::class, 'login'])->name('login.post');

    Route::get('/auth/refresh', [AuthenticationController::class, 'refresh'])->name('auth.refresh');
    // Register
    Route::get('/register', [AuthenticationController::class, 'register_view'])->name('register');
    Route::post('/register', [AuthenticationController::class, 'register'])->name('register.post');
    // Protected
    Route::group(['middleware' => 'checkAuth:' . env('API_APP', 'bakery-store')], function () {

        Route::get('/logout', [AuthenticationController::class, 'logout'])->name('logout');
        // Checkout
        Route::get('/checkout', [CheckoutController::class, 'view'])->name('checkout');

        Route::post('/checkout/create-payment', [CheckoutController::class, 'create_payment'])->name('checkout.create-payment');
        // Account Settings Group
        Route::group([
            'prefix' => 'account-settings',
        ], function () {

            Route::get('/checkout', [CheckoutController::class, 'view'])->name('checkout');
            // Account Settings
            Route::get('/', [AccountSettingsController::class, 'view'])->name('account-settings.information');
            Route::post('/update-profile', [AccountSettingsController::class, 'update_profile'])->name('account-settings.update-profile');

            // Account Settings - Address
            Route::get('/address', [AccountSettingsController::class, 'address_view'])->name('account-settings.address');
            Route::post('/address/create', [AccountSettingsController::class, 'create_address'])->name('account-settings.create-address');
            Route::post('/address/update', [AccountSettingsController::class, 'update_address'])->name('account-settings.update-address');
            Route::post('/address/delete', [AccountSettingsController::class, 'delete_address'])->name('account-settings.delete-address');
            // Account Settings - Transaction
            Route::get('/transactions', [AccountSettingsController::class, 'transactions_view'])->name('account-settings.transaction');
            Route::get('/transactions/get', [AccountSettingsController::class, 'transactions_get'])->name('account-settings.transaction');
            Route::post('/transactions/detail', [AccountSettingsController::class, 'transactions_detail'])->name('account-settings.transaction.detail');
            Route::post('/transactions/cancel', [AccountSettingsController::class, 'transactions_cancel'])->name('account-settings.transaction.cancel');
            // Account Settings - Voucher
            Route::get('/vouchers', [AccountSettingsController::class, 'vouchers_view'])->name('account-settings.vouchers');
        });
    });
});
