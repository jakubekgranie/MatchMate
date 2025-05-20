<?php

namespace Database\Seeders;

use App\Models\AccountLevel;
use App\Models\League;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserChangeStatuses;
use App\Models\UserChangeTypes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /* User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

        League::factory(3)->create();

        Role::create(['title' => 'Gracz']);
        Role::create(['title' => 'Kapitan drużyny']);
        Role::create(['title' => 'Administrator']);

        $team_count = 10;
        Team::factory($team_count)->create();

        for($user_count = 0; $user_count < $team_count * 8; $user_count++) {
            $teams = Team::with('users')->get();
            $targetTeam = $teams->first(function ($team) {
                return $team->users->count() < 8;
            });
            $role = $targetTeam->users->isEmpty() ? 2 : 1;

            User::factory()->create([
                'team_id' => $targetTeam->id,
                'is_reserve' => $targetTeam->users->count() > 4,
                'role_id' => $role,
            ]);
        }

        User::create([
            "name" => "Natan",
            "surname" => "Agent",
            "team_id" => 1,
            "email" => "agencik@gmail.com",
            "password" => Hash::make("Testowy123!"),
            "height" => 192,
            "weight" => 76,
            "age" => 28,
            "awaiting_review" => false
        ]);

        UserChangeTypes::create(["type" => "password_change"]);
        UserChangeTypes::create(["type" => "email_change"]);
        UserChangeTypes::create(["type" => "account_deletion"]);
        UserChangeTypes::create(["type" => "user_review"]);
        UserChangeStatuses::create(["status" => "pending"]);
        UserChangeStatuses::create(["status" => "expired"]);
        UserChangeStatuses::create(["status" => "overriden"]);
        UserChangeStatuses::create(["status" => "completed"]);
    }
}
