<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', MainPageController::class);

Route::controller(SessionController::class)->middleware('guest')->group(function () {
    Route::get('/login', 'index');
});
Route::controller(SessionController::class)->middleware('auth')->group(function () {

});

Route::controller(AccountController::class)->middleware('guest')->group(function () {
    Route::get('/register', 'create');
    Route::post('/register', 'store');
});
Route::controller(AccountController::class)->middleware('auth')->group(function () {

});
