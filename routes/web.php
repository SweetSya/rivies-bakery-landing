<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');
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
})->name('cart');
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
