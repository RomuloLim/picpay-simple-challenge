<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/email/verify/{id}/{hash}', function (Request $request) {
    $user = User::find($request->route('id'));

    if (!hash_equals((string)$user->getKey(), (string)$request->route('id'))) {
        return false;
    }

    if (!hash_equals(sha1($user->getEmailForVerification()), (string)$request->route('hash'))) {
        return false;
    }

    $user->markEmailAsVerified();

    dd('Email verified');
})->middleware('signed')->name('verification.verify');
