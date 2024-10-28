<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\CartController;
use \App\Http\Controllers\ItemController;
use \App\Http\Controllers\TransactionController;
use \App\Http\Controllers\UserController;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'loginPost'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function() {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('cashier', [CartController::class, 'index'])->name('cashier.index');
    Route::get('/cashier/addToCart/{id}', [CartController::class, 'addToCart'])->name('cashier.addToCart');
    Route::get('/cashier/clearCart', [CartController::class, 'clearCart'])->name('cashier.clearCart');
    Route::get('/cashier/downQty/{id}', [CartController::class, 'downQty'])->name('cashier.downQty');
    Route::get('/cashier/upQty/{id}', [CartController::class, 'upQty'])->name('cashier.upQty');
    Route::get('/cashier/updateQty/{id}/{qty}', [CartController::class, 'updateQty']);
    Route::get('/cashier/getItem/{id}', [CartController::class, 'getItem'])->name('cashier.getItem');
    Route::get('/cashier/updateCash/{cash}', [CartController::class, 'updateCash']);
    Route::post('/cashier/submitPayment', [CartController::class, 'submitPayment'])->name('cashier.submitPayment');
    Route::get('/cashier/bill', [CartController::class, 'bill'])->name('cashier.bill');
    Route::get('sales', [CartController::class, 'sales'])->name('sales.index');
    Route::delete('sales/destroy', [CartController::class, 'destroy'])->name('sales.destroy');

    Route::get('items', [ItemController::class, 'index'])->name('items.index');
    Route::get('items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('items/store', [ItemController::class, 'store'])->name('items.store');
    Route::get('items/edit/{item}', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('items/update/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('items/destroy', [ItemController::class, 'destroy'])->name('items.destroy');
    Route::get('items/view/{item}', [ItemController::class, 'view'])->name('items.view');

    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('transactions/store', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('transactions/edit/{transaction}', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('transactions/update/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('transactions/destroy', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('transactions/view/{transaction}', [TransactionController::class, 'view'])->name('transactions.view');
    Route::get('transactions/{id}/invoice', [TransactionController::class, 'invoice'])->name('transactions.invoice');

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('users/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/update/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/destroy', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/email/check', [UserController::class, 'emailcheck'])->name('users.emailcheck');

    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile.index');
    Route::put('/profile', [AuthController::class, 'update'])->name('password.update');

    Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->middleware(['auth']);
});