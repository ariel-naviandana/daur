<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/profile', [PageController::class, 'profile'])->name('profile');
Route::get('/chat', [PageController::class, 'chat'])->name('chat');
Route::get('/recycle', [PageController::class, 'recycle'])->name('recycle');
Route::get('/riwayat', [PageController::class, 'riwayat'])->name('riwayat');
Route::get('/saldo', [PageController::class, 'saldo'])->name('saldo');
Route::get('/artikel', [PageController::class, 'artikel'])->name('artikel');
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::get('/register', [PageController::class, 'register'])->name('register');

Route::get('/admin', [AdminPageController::class, 'home'])->name('home-admin');
Route::get('/admin/recycle', [AdminPageController::class, 'recycleManagement'])->name('manajemen-recycle');
Route::get('/admin/users', [AdminPageController::class, 'userManagement'])->name('admin.users');
Route::get('/admin/artikel', [AdminPageController::class, 'articleManagement'])->name('manajemen-artikel');
Route::get('/admin/sampah', [AdminPageController::class, 'wasteItemManagement'])->name('manajemen-sampah');
Route::get('/admin/saldo', [AdminPageController::class, 'saldoManagement'])->name('manajemen-saldo');

Route::get('/users', [UserController::class, 'getAll']);
