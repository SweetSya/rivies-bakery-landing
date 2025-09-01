<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


// Testing
Route::group([
    'middleware' => 'checkAuth:default'
], function () {
    // Account Settings
    Route::get('/payment-midtrans', [PaymentController::class, 'createPayment']);

    // Home
    Route::get('/', [HomeController::class, 'view'])->name('home');

    // Products
    Route::get('/products', [ProductController::class, 'view'])->name('products');
    Route::get('/products/fetch', [ProductController::class, 'fetch']);
    Route::get('/products/detail', [ProductController::class, 'single'])->name('product.detail');
    Route::get('/cart', function () {
        return Inertia::render('Cart');

        // Cart and Checkout
    })->name('cart');
    Route::post('/cart/fetch-prices', [CartController::class, 'fetch_prices'])->name('cart.fetch.prices');
    Route::get('/checkout', [CartController::class, 'view'])->name('checkout');
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

    // Login
    Route::get('/login', [AuthenticationController::class, 'view'])->name('login');
    Route::post('/login', [AuthenticationController::class, 'login'])->name('login.post');
    // Register
    Route::get('/register', function () {
        return Inertia::render('Register');
    })->name('register');
    // Protected
    Route::group(['middleware' => 'checkAuth:' . env('API_APP', 'bakery-store')], function () {

        Route::get('/logout', [AuthenticationController::class, 'logout'])->name('logout');

        // Account Settings Group
        Route::group([
            'prefix' => 'account-settings',
        ], function () {

            // Account Settings
            Route::get('/', function () {
                return Inertia::render('AccountSettings/AccountSettingsInformation');
            })->name('account-settings.information');
            // Account Settings - Address
            Route::get('/address', function () {
                return Inertia::render('AccountSettings/AccountSettingsAddress');
            })->name('account-settings.address');
            // Account Settings - Transaction
            Route::get('/transactions', function () {
                return Inertia::render('AccountSettings/AccountSettingsTransaction');
            })->name('account-settings.transaction');
        });
    });
});
