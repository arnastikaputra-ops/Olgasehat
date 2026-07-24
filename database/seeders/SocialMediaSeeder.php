<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialMedia = [
            [
                'icon' => 'fab fa-facebook-f',
                'title' => null,
                'url' => 'https://www.facebook.com',
            ],
            [
                'icon' => 'fab fa-youtube',
                'title' => null,
                'url' => 'https://www.youtube.com',
            ],
            [
                'icon' => 'fab fa-instagram',
                'title' => null,
                'url' => 'https://www.instagram.com',
            ],
        ];

        foreach ($socialMedia as $media) {
            \App\Models\SocialMedia::updateOrCreate(
                ['icon' => $media['icon']],
                $media
            );
        }
    }
}
