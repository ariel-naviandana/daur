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

Route::get('/manajemen-recycle', function () {
    return view('manajemen-recycle');
})->name('manajemen-recycle');

Route::get('/users', [UserController::class, 'getAll']);

Route::get('/admin/users', function () {
    return view('admin-users');
})->name('admin.users');

Route::get('/saldo', function () {
    return view('saldo');
})->name('saldo');
