<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shortlink;

class ShortlinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shortlinks = [
            [
                'title' => 'Visit AdsteriX - Earn 50 Coins',
                'url' => 'https://example.com/adsterix',
                'coins' => 50,
                'active' => true,
            ],
            [
                'title' => 'Complete Short Survey - Earn 100 Coins',
                'url' => 'https://example.com/survey',
                'coins' => 100,
                'active' => true,
            ],
            [
                'title' => 'Watch Video Ad - Earn 25 Coins',
                'url' => 'https://example.com/video-ad',
                'coins' => 25,
                'active' => true,
            ],
            [
                'title' => 'Play Mini Game - Earn 75 Coins',
                'url' => 'https://example.com/mini-game',
                'coins' => 75,
                'active' => true,
            ],
            [
                'title' => 'Visit Sponsor Website - Earn 60 Coins',
                'url' => 'https://example.com/sponsor',
                'coins' => 60,
                'active' => true,
            ],
        ];

        foreach ($shortlinks as $shortlink) {
            Shortlink::create($shortlink);
        }
    }
}
