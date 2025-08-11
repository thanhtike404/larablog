<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Frontend Development',
                'slug' => 'frontend-development'
            ],
            [
                'name' => 'Backend Development',
                'slug' => 'backend-development'
            ],
            [
                'name' => 'DevOps & Deployment',
                'slug' => 'devops-deployment'
            ],
            [
                'name' => 'Database & Storage',
                'slug' => 'database-storage'
            ],
            [
                'name' => 'API Development',
                'slug' => 'api-development'
            ],
            [
                'name' => 'Testing & Quality',
                'slug' => 'testing-quality'
            ],
            [
                'name' => 'Performance & Optimization',
                'slug' => 'performance-optimization'
            ],
            [
                'name' => 'Security',
                'slug' => 'security'
            ]
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
