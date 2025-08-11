<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();
        $categories = \App\Models\Category::all();
        $tags = \App\Models\Tag::all();

        $posts = [
            [
                'title' => 'Building a Modern Laravel API with Docker and Sail',
                'slug' => 'building-modern-laravel-api-docker-sail',
                'content' => 'In this comprehensive guide, we\'ll explore how to build a robust REST API using Laravel 11, containerize it with Docker, and streamline development with Laravel Sail. We\'ll cover authentication, validation, and best practices for API development.',
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'user_id' => $users->random()->id,
                'category_id' => $categories->where('slug', 'backend-development')->first()->id,
                'tags' => ['laravel', 'docker']
            ],
            [
                'title' => 'Vue.js 3 Composition API: A Complete Guide',
                'slug' => 'vuejs-3-composition-api-complete-guide',
                'content' => 'Dive deep into Vue.js 3\'s Composition API and learn how it revolutionizes component development. We\'ll build a real-world application showcasing reactive data, computed properties, and lifecycle hooks.',
                'is_published' => true,
                'published_at' => now()->subDays(10),
                'user_id' => $users->random()->id,
                'category_id' => $categories->where('slug', 'frontend-development')->first()->id,
                'tags' => ['vue-js']
            ],
            [
                'title' => 'React Hooks vs Vue Composition API: Performance Comparison',
                'slug' => 'react-hooks-vs-vue-composition-api-performance',
                'content' => 'A detailed comparison between React Hooks and Vue.js Composition API, analyzing performance, developer experience, and use cases. Includes benchmarks and real-world examples.',
                'is_published' => true,
                'published_at' => now()->subDays(15),
                'user_id' => $users->random()->id,
                'category_id' => $categories->where('slug', 'performance-optimization')->first()->id,
                'tags' => ['react', 'vue-js']
            ],
            [
                'title' => 'Styling Modern Web Apps with Tailwind CSS and Alpine.js',
                'slug' => 'styling-modern-web-apps-tailwind-alpine',
                'content' => 'Learn how to create beautiful, responsive web applications using Tailwind CSS for styling and Alpine.js for interactive behavior. Perfect for developers who want to avoid heavy JavaScript frameworks.',
                'is_published' => true,
                'published_at' => now()->subDays(20),
                'user_id' => $users->random()->id,
                'category_id' => $categories->where('slug', 'frontend-development')->first()->id,
                'tags' => ['tailwind-css', 'alpine-js']
            ],
            [
                'title' => 'Laravel Livewire: Building Dynamic UIs Without JavaScript',
                'slug' => 'laravel-livewire-dynamic-uis-without-javascript',
                'content' => 'Discover how Laravel Livewire enables you to build dynamic, reactive user interfaces using only PHP. We\'ll create a real-time dashboard with forms, validation, and live updates.',
                'is_published' => true,
                'published_at' => now()->subDays(25),
                'user_id' => $users->random()->id,
                'category_id' => $categories->where('slug', 'backend-development')->first()->id,
                'tags' => ['laravel', 'livewire']
            ],
            [
                'title' => 'Inertia.js: The Modern Monolith Approach',
                'slug' => 'inertiajs-modern-monolith-approach',
                'content' => 'Explore Inertia.js and how it bridges the gap between traditional server-side applications and modern SPAs. Build a full-stack application with Laravel backend and Vue.js frontend.',
                'is_published' => true,
                'published_at' => now()->subDays(30),
                'user_id' => $users->random()->id,
                'category_id' => $categories->where('slug', 'api-development')->first()->id,
                'tags' => ['laravel', 'vue-js', 'inertia-js']
            ],
            [
                'title' => 'Docker Best Practices for Laravel Development',
                'slug' => 'docker-best-practices-laravel-development',
                'content' => 'Master Docker containerization for Laravel applications. Learn about multi-stage builds, optimization techniques, and production deployment strategies.',
                'is_published' => true,
                'published_at' => now()->subDays(35),
                'user_id' => $users->random()->id,
                'category_id' => $categories->where('slug', 'devops-deployment')->first()->id,
                'tags' => ['docker', 'laravel']
            ],
            [
                'title' => 'Building Secure APIs: Authentication and Authorization',
                'slug' => 'building-secure-apis-authentication-authorization',
                'content' => 'A comprehensive guide to API security covering JWT tokens, OAuth2, rate limiting, and input validation. Protect your Laravel APIs from common vulnerabilities.',
                'is_published' => true,
                'published_at' => now()->subDays(40),
                'user_id' => $users->random()->id,
                'category_id' => $categories->where('slug', 'security')->first()->id,
                'tags' => ['laravel']
            ],
            [
                'title' => 'React Performance Optimization Techniques',
                'slug' => 'react-performance-optimization-techniques',
                'content' => 'Learn advanced React optimization techniques including memoization, code splitting, lazy loading, and bundle analysis. Improve your app\'s performance significantly.',
                'is_published' => false,
                'published_at' => null,
                'user_id' => $users->random()->id,
                'category_id' => $categories->where('slug', 'performance-optimization')->first()->id,
                'tags' => ['react']
            ],
            [
                'title' => 'Testing Laravel Applications: Unit, Feature, and Browser Tests',
                'slug' => 'testing-laravel-applications-comprehensive-guide',
                'content' => 'Master testing in Laravel with PHPUnit. Cover unit tests, feature tests, database testing, and browser automation with Laravel Dusk.',
                'is_published' => false,
                'published_at' => null,
                'user_id' => $users->random()->id,
                'category_id' => $categories->where('slug', 'testing-quality')->first()->id,
                'tags' => ['laravel']
            ]
        ];

        foreach ($posts as $postData) {
            $tagSlugs = $postData['tags'];
            unset($postData['tags']);

            $post = \App\Models\BlogPost::create($postData);

            // Attach tags to the post
            $tagIds = $tags->whereIn('slug', $tagSlugs)->pluck('id')->toArray();
            $post->tags()->attach($tagIds);
        }
    }
}
