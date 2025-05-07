<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Equivalent to unavailable `ON DELETE(set default)` @see \app\database\migrations\2025_05_07_080902_create_users_table.php
         */
        Role::deleting(function ($role) {
            User::where('role_id', $role->id)->update(['role_id' => 0]);
        });
    }
}
