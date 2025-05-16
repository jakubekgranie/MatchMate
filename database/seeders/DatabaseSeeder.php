<?php

namespace Database\Seeders;

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
            $team_id = 0;
            $teams = Team::with('users')->get();
            foreach($teams as $team)
                if (sizeof($team->users) == 8)
                    $team_id++;

            User::factory()->create([
                'team_id' => $team_id + 1,
                'is_reserve' => sizeof($teams[$team_id]->users) > 4,
            ]);
        }

        User::create([
            "name" => "Natan",
            "surname" => "Agent",
            "email" => "agencik@gmail.com",
            "password" => Hash::make("testowy"),
            "height" => 192,
            "weight" => 76,
            "age" => 28
        ]);

        UserChangeTypes::create(["type" => "password_change"]);
        UserChangeTypes::create(["type" => "email_change"]);
        UserChangeStatuses::create(["status" => "pending"]);
        UserChangeStatuses::create(["status" => "expired"]);
        UserChangeStatuses::create(["status" => "overriden"]);
    }
}
