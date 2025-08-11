<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            [
                'name' => 'Laravel',
                'slug' => 'laravel',
                'color' => '#FF2D20',
                'description' => 'The PHP Framework for Web Artisans',
                'official_url' => 'https://laravel.com'
            ],
            [
                'name' => 'Vue.js',
                'slug' => 'vue-js',
                'color' => '#4FC08D',
                'description' => 'The Progressive JavaScript Framework',
                'official_url' => 'https://vuejs.org'
            ],
            [
                'name' => 'React',
                'slug' => 'react',
                'color' => '#61DAFB',
                'description' => 'A JavaScript library for building user interfaces',
                'official_url' => 'https://reactjs.org'
            ],
            [
                'name' => 'Docker',
                'slug' => 'docker',
                'color' => '#2496ED',
                'description' => 'Platform for developing, shipping, and running applications',
                'official_url' => 'https://docker.com'
            ],
            [
                'name' => 'Tailwind CSS',
                'slug' => 'tailwind-css',
                'color' => '#06B6D4',
                'description' => 'A utility-first CSS framework',
                'official_url' => 'https://tailwindcss.com'
            ],
            [
                'name' => 'Alpine.js',
                'slug' => 'alpine-js',
                'color' => '#8BC34A',
                'description' => 'A rugged, minimal framework for composing JavaScript behavior',
                'official_url' => 'https://alpinejs.dev'
            ],
            [
                'name' => 'Inertia.js',
                'slug' => 'inertia-js',
                'color' => '#9553E9',
                'description' => 'The Modern Monolith',
                'official_url' => 'https://inertiajs.com'
            ],
            [
                'name' => 'Livewire',
                'slug' => 'livewire',
                'color' => '#FB70A9',
                'description' => 'A full-stack framework for Laravel',
                'official_url' => 'https://laravel-livewire.com'
            ]
        ];

        foreach ($tags as $tag) {
            \App\Models\Tag::create($tag);
        }
    }
}
