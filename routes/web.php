<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Route::resource('transactions', TransactionController::class)->only([
    'index', 'create', 'store'
]);
Route::view('/', 'dashboard')->name('dashboard');