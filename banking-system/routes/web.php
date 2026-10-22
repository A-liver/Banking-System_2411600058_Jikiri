<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('transactions', TransactionController::class)
    ->only(['index', 'create', 'store']);

Route::view('/dashboard-stats', 'dashboard');