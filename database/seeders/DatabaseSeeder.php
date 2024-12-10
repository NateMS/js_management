<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use App\Models\CourseType;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::updateOrCreate(
        //     ['email' => 'nadim@salloum.ch'],
        //     [
        //         'name' => 'Nadim Salloum',
        //         'email' => 'nadim@salloum.ch',
        //         'password' => Hash::make('abcd1234'),
        //     ]
        // );

        // Team::create([
        //     'name' => 'Kutu / Getu Knaben',
        //     'user_id' => 1,
        // ]);

        // Team::create([
        //     'name' => 'Getu Mädchen',
        //     'user_id' => 1,
        // ]);

        CourseType::create([
            'name' => 'Grundkurs Kutu / Getu',
            'minimum_age' => 17,
            'order' => 1,
        ]);

        CourseType::create([
            'name' => 'FK Kutu / Getu',
            'minimum_age' => 17,
            'order' => 2,
        ]);

        CourseType::create([
            'name' => '1418',
            'minimum_age' => 14,
            'maximum_age' => 18,
            'order' => 3,
        ]);

        // User::factory()->create([
        //     'name' => 'Nadim Salloum',
        //     'email' => 'test@example.com',
        // ]);
    }
}
