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

        Team::create([
            'name' => 'Kutu / Getu Knaben',
        ]);

        Team::create([
            'name' => 'Getu Mädchen',
        ]);

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

        User::updateOrCreate(
            ['email' => 'nadim@salloum.ch'],
            [
                'name' => 'Nadim Salloum',
                'email' => 'nadim@salloum.ch',
                'password' => Hash::make('abcd1234'),
                'is_manager' => true
            ]
        );

        // User::factory()->create([
        //     'name' => 'Nadim Salloum',
        //     'email' => 'test@example.com',
        // ]);
    }
}
