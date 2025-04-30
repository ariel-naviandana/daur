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

Route::get('/chat', function() {
    return view('chat');
})->name('chat');

Route::get('/users', [UserController::class, 'getAll']);

Route::get('/admin/users', function () {
    return view('admin-users');
})->name('admin.users');

