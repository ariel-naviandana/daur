<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/chat', function () {
    return view('chat');
})->name('chat');

Route::get('/recycle', function () {
    return view('recycle');
})->name('recycle');

Route::get('/riwayat', function () {
    return view('riwayat-recycle');
})->name('riwayat');

Route::get('/riwayat-admin', function () {
    return view('riwayat-recycle-admin');
})->name('riwayat-admin');
