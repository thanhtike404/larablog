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
                'excerpt' => 'Learn how to build a robust REST API using Laravel 11, containerize it with Docker, and streamline development with Laravel Sail.',
                'featured_image' => '/storage/images/laravel-docker.jpg',
                'content_blocks' => [
                    'blocks' => [
                        [
                            'id' => 'intro',
                            'type' => 'paragraph',
                            'data' => [
                                'text' => 'In this comprehensive guide, we\'ll explore how to build a robust REST API using Laravel 11, containerize it with Docker, and streamline development with Laravel Sail.'
                            ],
                            'order' => 1
                        ],
                        [
                            'id' => 'setup-heading',
                            'type' => 'heading',
                            'data' => [
                                'text' => 'Setting Up Laravel Sail',
                                'level' => 2
                            ],
                            'order' => 2
                        ],
                        [
                            'id' => 'setup-content',
                            'type' => 'paragraph',
                            'data' => [
                                'text' => 'Laravel Sail provides a simple command-line interface for interacting with Laravel\'s default Docker development environment.'
                            ],
                            'order' => 3
                        ],
                        [
                            'id' => 'code-example',
                            'type' => 'code',
                            'data' => [
                                'code' => 'composer create-project laravel/laravel my-api\ncd my-api\nphp artisan sail:install',
                                'language' => 'bash'
                            ],
                            'order' => 4
                        ]
                    ]
                ],
                'seo_meta' => [
                    'title' => 'Building a Modern Laravel API with Docker and Sail - Complete Guide',
                    'description' => 'Learn how to build a robust REST API using Laravel 11, containerize it with Docker, and streamline development with Laravel Sail.',
                    'keywords' => ['laravel', 'docker', 'api', 'sail', 'php']
                ],
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'user_id' => $users->random()->id,
                'category_id' => $categories->where('slug', 'backend-development')->first()->id,
                'tags' => ['laravel', 'docker']
            ],
            [
                'title' => 'Vue.js 3 Composition API: A Complete Guide',
                'slug' => 'vuejs-3-composition-api-complete-guide',
                'excerpt' => 'Dive deep into Vue.js 3\'s Composition API and learn how it revolutionizes component development.',
                'featured_image' => '/storage/images/vue-composition.jpg',
                'content_blocks' => [
                    'blocks' => [
                        [
                            'id' => 'intro',
                            'type' => 'paragraph',
                            'data' => [
                                'text' => 'Dive deep into Vue.js 3\'s Composition API and learn how it revolutionizes component development. We\'ll build a real-world application showcasing reactive data, computed properties, and lifecycle hooks.'
                            ],
                            'order' => 1
                        ],
                        [
                            'id' => 'benefits',
                            'type' => 'heading',
                            'data' => [
                                'text' => 'Benefits of Composition API',
                                'level' => 2
                            ],
                            'order' => 2
                        ],
                        [
                            'id' => 'benefits-list',
                            'type' => 'list',
                            'data' => [
                                'items' => [
                                    'Better TypeScript support',
                                    'More flexible code organization',
                                    'Easier logic reuse between components',
                                    'Better performance in large applications'
                                ],
                                'type' => 'unordered'
                            ],
                            'order' => 3
                        ]
                    ]
                ],
                'seo_meta' => [
                    'title' => 'Vue.js 3 Composition API: A Complete Guide',
                    'description' => 'Master Vue.js 3 Composition API with practical examples and best practices.',
                    'keywords' => ['vue.js', 'composition api', 'javascript', 'frontend']
                ],
                'is_published' => true,
                'published_at' => now()->subDays(10),
                'user_id' => $users->random()->id,
                'category_id' => $categories->where('slug', 'frontend-development')->first()->id,
                'tags' => ['vue-js']
            ],
            [
                'title' => 'React Hooks vs Vue Composition API: Performance Comparison',
                'slug' => 'react-hooks-vs-vue-composition-api-performance',
                'excerpt' => 'A detailed comparison between React Hooks and Vue.js Composition API, analyzing performance and developer experience.',
                'featured_image' => '/storage/images/react-vs-vue.jpg',
                'content_blocks' => [
                    'blocks' => [
                        [
                            'id' => 'intro',
                            'type' => 'paragraph',
                            'data' => [
                                'text' => 'A detailed comparison between React Hooks and Vue.js Composition API, analyzing performance, developer experience, and use cases. Includes benchmarks and real-world examples.'
                            ],
                            'order' => 1
                        ],
                        [
                            'id' => 'comparison-image',
                            'type' => 'image',
                            'data' => [
                                'url' => '/storage/images/performance-chart.jpg',
                                'alt' => 'Performance comparison chart between React and Vue',
                                'caption' => 'Performance benchmarks showing render times and memory usage'
                            ],
                            'order' => 2
                        ],
                        [
                            'id' => 'quote',
                            'type' => 'quote',
                            'data' => [
                                'text' => 'Both frameworks offer excellent performance, but the choice often comes down to team expertise and project requirements.',
                                'author' => 'Frontend Developer',
                                'cite' => 'Performance Study 2024'
                            ],
                            'order' => 3
                        ]
                    ]
                ],
                'is_published' => true,
                'published_at' => now()->subDays(15),
                'user_id' => $users->random()->id,
                'category_id' => $categories->where('slug', 'performance-optimization')->first()->id,
                'tags' => ['react', 'vue-js']
            ]
        ];

        foreach ($posts as $postData) {
            $tagSlugs = $postData['tags'];
            unset($postData['tags']);

            $post = \App\Models\BlogPost::create($postData);

            // Calculate reading time
            $post->calculateReadingTime();
            $post->save();

            // Attach tags to the post
            $tagIds = $tags->whereIn('slug', $tagSlugs)->pluck('id')->toArray();
            $post->tags()->attach($tagIds);
        }
    }
}
