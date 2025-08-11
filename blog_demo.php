<?php

// Blog System Demo - Run with: ./vendor/bin/sail artisan tinker < blog_demo.php

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;

echo "=== Blog System Demo ===\n\n";

// 1. Get all published posts with their tags and categories
echo "1. Published Posts with Tags:\n";
$publishedPosts = BlogPost::where('is_published', true)
    ->with(['tags', 'category', 'user'])
    ->get();

foreach ($publishedPosts as $post) {
    echo "- {$post->title}\n";
    echo "  Category: {$post->category->name}\n";
    echo "  Tags: " . $post->tags->pluck('name')->join(', ') . "\n";
    echo "  Author: {$post->user->name}\n\n";
}

// 2. Get all posts that use Laravel
echo "2. Posts using Laravel:\n";
$laravelPosts = BlogPost::whereHas('tags', function ($query) {
    $query->where('slug', 'laravel');
})->with('tags')->get();

foreach ($laravelPosts as $post) {
    echo "- {$post->title}\n";
}
echo "\n";

// 3. Get most popular tags (used in most posts)
echo "3. Most Popular Tags:\n";
$popularTags = Tag::withCount('posts')
    ->orderBy('posts_count', 'desc')
    ->get();

foreach ($popularTags as $tag) {
    echo "- {$tag->name}: {$tag->posts_count} posts\n";
}
echo "\n";

// 4. Get posts by category
echo "4. Frontend Development Posts:\n";
$frontendPosts = BlogPost::whereHas('category', function ($query) {
    $query->where('slug', 'frontend-development');
})->with('tags')->get();

foreach ($frontendPosts as $post) {
    echo "- {$post->title}\n";
    echo "  Technologies: " . $post->tags->pluck('name')->join(', ') . "\n";
}
echo "\n";

// 5. Statistics
echo "5. Blog Statistics:\n";
echo "Total Posts: " . BlogPost::count() . "\n";
echo "Published Posts: " . BlogPost::where('is_published', true)->count() . "\n";
echo "Draft Posts: " . BlogPost::where('is_published', false)->count() . "\n";
echo "Total Categories: " . Category::count() . "\n";
echo "Total Tags: " . Tag::count() . "\n";

echo "\n=== Blog system is ready to use! ===\n";
