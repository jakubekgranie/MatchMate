<?php

use App\Models\User;
use App\Models\UserChangeStatuses;
use App\Models\UserChangeTypes;
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
        Schema::create('pending_user_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('url_key');
            $table->foreignIdFor(UserChangeTypes::class)
                ->constrained('user_change_types')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(UserChangeStatuses::class)->default(1)
                ->constrained('user_change_statuses')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'url_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_user_changes');
    }
};
