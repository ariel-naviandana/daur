<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WalletTransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WasteTypeController;
use App\Http\Controllers\RecycleTransactionController;
use App\Http\Controllers\RecycleTransactionItemController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\AuthController;
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

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

Route::get('/wallets', [WalletController::class, 'index']);
Route::post('/wallets', [WalletController::class, 'store']);
Route::get('/wallets/{id}', [WalletController::class, 'show']);
Route::put('/wallets/{id}', [WalletController::class, 'update']);
Route::delete('/wallets/{id}', [WalletController::class, 'destroy']);

Route::get('/wallet_transactions', [WalletTransactionController::class, 'index']);
Route::post('/wallet_transactions', [WalletTransactionController::class, 'store']);
Route::get('/wallet_transactions/{id}', [WalletTransactionController::class, 'show']);
Route::put('/wallet_transactions/{id}', [WalletTransactionController::class, 'update']);
Route::delete('/wallet_transactions/{id}', [WalletTransactionController::class, 'destroy']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

Route::get('/waste-types', [WasteTypeController::class, 'index']);
Route::post('/waste-types', [WasteTypeController::class, 'store']);
Route::get('/waste-types/{id}', [WasteTypeController::class, 'show']);
Route::get('/waste-types/category/{categoryId}', [WasteTypeController::class, 'getByCategory']);
Route::put('/waste-types/{id}', [WasteTypeController::class, 'update']);
Route::delete('/waste-types/{id}', [WasteTypeController::class, 'destroy']);

Route::get('/recycle-transactions', [RecycleTransactionController::class, 'index']);
Route::post('/recycle-transactions', [RecycleTransactionController::class, 'store']);
Route::get('/recycle-transactions/{id}', [RecycleTransactionController::class, 'show']);
Route::get('/recycle-transactions/user/{userId}', [RecycleTransactionController::class, 'getByUser']);
Route::put('/recycle-transactions/{id}', [RecycleTransactionController::class, 'update']);
Route::delete('/recycle-transactions/{id}', [RecycleTransactionController::class, 'destroy']);

Route::get('/recycle-transaction-items', [RecycleTransactionItemController::class, 'index']);
Route::post('/recycle-transaction-items', [RecycleTransactionItemController::class, 'store']);
Route::get('/recycle-transaction-items/{id}', [RecycleTransactionItemController::class, 'show']);
Route::get('/recycle-transaction-items/transaction/{transactionId}', [RecycleTransactionItemController::class, 'getByTransaction']);
Route::put('/recycle-transaction-items/{id}', [RecycleTransactionItemController::class, 'update']);
Route::delete('/recycle-transaction-items/{id}', [RecycleTransactionItemController::class, 'destroy']);

Route::get('/articles', [ArticleController::class, 'index']);
Route::post('/articles', [ArticleController::class, 'store']);
Route::post('/upload', [ArticleController::class, 'uploadImage']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);
Route::put('/articles/{id}', [ArticleController::class, 'update']);
Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);

Route::get('/chats', [ChatController::class, 'index']);
Route::post('/chats', [ChatController::class, 'store']);
Route::get('/chats/{id}', [ChatController::class, 'show']);
Route::put('/chats/{id}', [ChatController::class, 'update']);
Route::delete('/chats/{id}', [ChatController::class, 'destroy']);

Route::get('/banks', [BankController::class, 'index']);
Route::post('/banks', [BankController::class, 'store']);
Route::get('/banks/{id}', [BankController::class, 'show']);
Route::put('/banks/{id}', [BankController::class, 'update']);
Route::delete('/banks/{id}', [BankController::class, 'destroy']);

Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
