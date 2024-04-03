<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    ds(fake()->cnpj(false));
});

Route::prefix('/user')->group(function () {
    Route::post('/', [UserController::class, 'store'])->name('user.store');
});
