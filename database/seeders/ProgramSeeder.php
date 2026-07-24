<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Program::create([
            'foto' => 'default.jpg',
            'title' => 'Pelayanan Terbaik',
            'description' => 'Kami memberikan pelayanan terbaik untuk memenuhi kebutuhan olahraga Anda.',
            'icon' => 'fas fa-handshake',
        ]);

        \App\Models\Program::create([
            'foto' => 'default.jpg',
            'title' => 'Vendor Terverifikasi',
            'description' => 'Kami hanya bekerjasama dengan mitra yang terverifikasi untuk pengalaman terbaik Anda.',
            'icon' => 'fas fa-shield-alt',
        ]);

        \App\Models\Program::create([
            'foto' => 'default.jpg',
            'title' => 'Komunitas Olahraga',
            'description' => 'Bergabunglah dengan komunitas olahraga kami untuk berbagi pengalaman dan motivasi.',
            'icon' => 'fas fa-users',
        ]);

        \App\Models\Program::create([
            'foto' => 'default.jpg',
            'title' => 'Event Olahraga',
            'description' => 'Ikuti berbagai event olahraga menarik yang kami selenggarakan.',
            'icon' => 'fas fa-calendar-alt',
        ]);
    }
}
