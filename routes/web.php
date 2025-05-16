<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', MainPageController::class);
Route::get('/test', function () {return view("mail.email-change");});

Route::controller(SessionController::class)->middleware('guest')->group(function () {
    Route::get('/login', 'create');
    Route::post('/login', 'store');
});
Route::controller(SessionController::class)->middleware('auth')->group(function () {
    Route::get('/profile', 'show');
    Route::delete('/logout', 'destroy');
});

Route::controller(AccountController::class)->middleware('guest')->group(function () {
    Route::get('/register', 'create');
    Route::post('/register', 'store');
});
Route::controller(AccountController::class)->middleware('auth')->group(function () {
    Route::patch('/profile/text', 'update');
    Route::patch('/profile/images', 'update');
    Route::patch('/profile/email', 'updateMail');
    Route::patch('/profile/password', 'updatePassword');
    Route::delete('/profile/delete', 'destroy');
});
