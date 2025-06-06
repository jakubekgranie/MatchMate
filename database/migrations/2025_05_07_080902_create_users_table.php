<?php

use App\Models\Role;
use App\Models\Team;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)
                ->nullable()
                ->constrained("teams")
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            $table->boolean('is_reserve')->default(false);
            $table->boolean('awaiting_review')->default(true);
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('pfp_name')->nullable();
            $table->string('banner_name')->nullable();
            $table->unsignedInteger('weight')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedInteger('age')->nullable();
            $table->foreignIdFor(Role::class)
                ->default(1)
                /**
                 * Deletion observer present (sets to guest level permissions). @see \App\Providers\AppServiceProvider
                 */
                ->constrained("roles")
                    ->cascadeOnUpdate();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
