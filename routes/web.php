<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CaptainController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', MainPageController::class);

Route::middleware('guest')->group(function () {
    Route::controller(SessionController::class)->group(function () {
        Route::get('/login', 'create');
        Route::post('/login', 'store');

        Route::get('/register', 'create');
        Route::post('/register', 'store');
    });
});

Route::middleware('auth')->group(function () {
    Route::controller(SessionController::class)->group(function () {
        Route::get('/profile', 'show');
        Route::delete('/logout', 'destroy');
    });

    Route::controller(CaptainController::class)->group(function () {
        Route::get('/profile/action/{uuid}/dashboard', 'create');
        Route::get('/profile/action/{uuid}/accept', 'store');
        Route::get('/profile/action/{uuid}/reject', 'destroy');
    });
    Route::controller(AccountController::class)->group(function () {
        Route::patch('/profile/text', 'update');
        Route::patch('/profile/images', 'update');
        Route::patch('/profile/email', 'updateMail');
        Route::patch('/profile/password', 'updatePassword');
        Route::get('/profile/action/{uuid}', 'confirmChange');
        Route::delete('/profile/delete', 'destroy');
    });
    Route::controller(TeamController::class)->group(function () {
        Route::get('/my-team', 'index');
        Route::patch('/my-team/text', 'update');
        Route::patch('/my-team/images', 'update');
        Route::patch('/my-team/move', 'changeStatus')->name("change-status");
    });
});
