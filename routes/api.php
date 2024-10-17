<?php

use App\Http\Controllers\{TransactionsController, UserController};
use Illuminate\Support\Facades\Route;

Route::prefix('/user')->group(function () {
    Route::post('/', [UserController::class, 'store'])->name('user.store');
});

Route::prefix('/transaction')->group(function () {
    Route::post('/', [TransactionsController::class, 'store'])->name('transaction.store');
});
