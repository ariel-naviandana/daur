<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/riwayat', function () {
    return view('riwayat-recycle');
})->name('riwayat');

Route::get('/riwayat-admin', function () {
    return view('riwayat-recycle-admin');
})->name('riwayat-admin');
