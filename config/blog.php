<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Blog Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the blog system
    |
    */

    'pagination' => [
        'posts_per_page' => env('BLOG_POSTS_PER_PAGE', 12),
        'related_posts' => env('BLOG_RELATED_POSTS', 3),
        'popular_posts' => env('BLOG_POPULAR_POSTS', 5),
        'featured_posts' => env('BLOG_FEATURED_POSTS', 3),
    ],

    'reading_time' => [
        'words_per_minute' => env('BLOG_WORDS_PER_MINUTE', 200),
    ],

    'cache' => [
        'ttl' => env('BLOG_CACHE_TTL', 3600), // 1 hour
        'enabled' => env('BLOG_CACHE_ENABLED', true),
    ],

    'seo' => [
        'default_meta_title' => env('BLOG_DEFAULT_META_TITLE', 'Blog'),
        'default_meta_description' => env('BLOG_DEFAULT_META_DESCRIPTION', 'Latest blog posts and articles'),
        'max_title_length' => 60,
        'max_description_length' => 160,
    ],

    'features' => [
        'comments' => env('BLOG_COMMENTS_ENABLED', false),
        'social_sharing' => env('BLOG_SOCIAL_SHARING_ENABLED', true),
        'reading_progress' => env('BLOG_READING_PROGRESS_ENABLED', true),
        'dark_mode' => env('BLOG_DARK_MODE_ENABLED', true),
    ],

    'content_blocks' => [
        'allowed_types' => [
            'paragraph',
            'heading',
            'image',
            'code',
            'quote',
            'list',
            'embed',
        ],
    ],
];
