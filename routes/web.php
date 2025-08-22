<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


// Testing
Route::get('/payment-midtrans', [PaymentController::class, 'createPayment']);

// Home
Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

// Products
Route::get('/products', function () {
    return Inertia::render('Product');
})->name('product');
Route::get('/products/{slug}', function ($id) {
    return Inertia::render('ProductDetail', [
        'id' => $id,
    ]);
})->name('product.detail');
Route::get('/cart', function () {
    return Inertia::render('Cart');

    // Cart and Checkout
})->name('cart');
Route::get('/checkout', function () {
    return Inertia::render('Checkout');
})->name('checkout');
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
Route::get('/login', function () {
    return Inertia::render('Login');
})->name('Login');
// Register
Route::get('/register', function () {
    return Inertia::render('Register');
})->name('register');
// Account Settings
Route::get('/account-settings', function () {
    return Inertia::render('AccountSettings/AccountSettingsInformation');
})->name('account-settings.information');
// Account Settings - Address
Route::get('/account-settings/address', function () {
    return Inertia::render('AccountSettings/AccountSettingsAddress');
})->name('account-settings.address');
// Account Settings - Transaction
Route::get('/account-settings/transactions', function () {
    return Inertia::render('AccountSettings/AccountSettingsTransaction');
})->name('account-settings.transaction');
