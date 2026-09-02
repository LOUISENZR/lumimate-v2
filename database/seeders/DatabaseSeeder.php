<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            IngredientSeeder::class,
            IngredientConflictSeeder::class,
            RuleSeeder::class,
            ConsultationQuestionSeeder::class,
        ]);

        // Default Administrator
        User::updateOrCreate(
            ['email' => 'admin@lumimate.com'],
            [
                'name' => 'LumiMate Administrator',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // Default Skincare User
        User::updateOrCreate(
            ['email' => 'user@lumimate.com'],
            [
                'name' => 'Demo User',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]
        );
    }
}
