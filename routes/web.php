<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');
Route::get('/products', function () {
    return Inertia::render('Product');
})->name('product');
Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');