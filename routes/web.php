<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
})->name('login');
