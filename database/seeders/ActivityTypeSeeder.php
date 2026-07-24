<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ActivityType::create([
            'name' => 'open-class',
            'title' => 'Komunitas',
            'icon' => 'fas fa-chalkboard-teacher',
        ]);

        \App\Models\ActivityType::create([
            'name' => 'klub',
            'title' => 'Membership',
            'icon' => 'fas fa-users',
        ]);

        \App\Models\ActivityType::create([
            'name' => 'event',
            'title' => 'Event Olahraga',
            'icon' => 'fas fa-calendar-alt',
        ]);
    }
}
