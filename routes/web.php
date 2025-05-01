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

Route::get('/admin/recycle', function () {
    return view('manajemen-recycle');
})->name('manajemen-recycle');

Route::get('/users', [UserController::class, 'getAll']);

Route::get('/admin/users', function () {
    return view('admin-users');
})->name('admin.users');

Route::get('/saldo', function () {
    return view('saldo');
})->name('saldo');

Route::get('/artikel', function () {
    return view('artikel');
})->name('artikel');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/admin', function () {
    return view('home-admin');
})->name('home-admin');

Route::get('/admin/artikel', function () {
    return view('manajemen-artikel');
})->name('manajemen-artikel');

Route::get('/admin/saldo', function () {
    return view('manajemen-saldo');
})->name('manajemen-saldo');
