<?php

// Example usage of the refactored blog system

use App\Actions\CreateBlogPostAction;
use App\DTOs\BlogPostData;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Services\BlogService;
use App\Services\BlogCacheService;

// Initialize services
$blogService = app(BlogService::class);
$cacheService = app(BlogCacheService::class);
$createAction = app(CreateBlogPostAction::class);

// Get sample data
$user = User::first();
$category = Category::where('slug', 'backend-development')->first();
$laravelTag = Tag::where('slug', 'laravel')->first();
$dockerTag = Tag::where('slug', 'docker')->first();

// Create a blog post using the new architecture
$postData = BlogPostData::fromArray([
    'title' => 'Building a REST API with Laravel and Docker',
    'content' => 'In this tutorial, we will explore how to build a robust REST API using Laravel framework and containerize it with Docker...',
    'excerpt' => 'Learn how to build and containerize a Laravel REST API',
    'is_published' => true,
    'category_id' => $category->id,
    'tag_ids' => [$laravelTag->id, $dockerTag->id],
    'seo_meta' => [
        'title' => 'Building REST API with Laravel & Docker',
        'description' => 'Complete guide to building and containerizing Laravel APIs',
        'keywords' => ['laravel', 'docker', 'api', 'rest']
    ]
]);

$post = $createAction->execute($postData, $user);

echo "✅ Blog post created successfully!\n";
echo "Title: {$post->title}\n";
echo "Slug: {$post->slug}\n";
echo "Reading time: {$post->reading_time} minutes\n\n";

// Query examples using the new service layer:

echo "=== Using the new Blog Service ===\n\n";

// 1. Get published posts with pagination
echo "1. Published Posts:\n";
$publishedPosts = $blogService->getPublishedPosts(5);
foreach ($publishedPosts as $post) {
    echo "- {$post->title} ({$post->views_count} views)\n";
}
echo "\n";

// 2. Get posts by tag using service
echo "2. Laravel Posts:\n";
$laravelPosts = $blogService->getPostsByTag('laravel', 5);
foreach ($laravelPosts as $post) {
    echo "- {$post->title}\n";
}
echo "\n";

// 3. Get popular tags using service
echo "3. Popular Tags:\n";
$popularTags = $blogService->getPopularTags(5);
foreach ($popularTags as $tag) {
    echo "- {$tag->name}: {$tag->posts_count} posts\n";
}
echo "\n";

// 4. Search posts
echo "4. Search Results for 'Laravel':\n";
$searchResults = $blogService->searchPosts('Laravel', 3);
foreach ($searchResults as $post) {
    echo "- {$post->title}\n";
}
echo "\n";

// 5. Cache examples
echo "5. Cached Data:\n";
$stats = $cacheService->getBlogStats();
echo "Total Posts: {$stats['total_posts']}\n";
echo "Total Views: {$stats['total_views']}\n";
echo "Total Categories: {$stats['total_categories']}\n";
echo "Total Tags: {$stats['total_tags']}\n\n";

echo "🚀 Refactored blog system is ready to use!\n";
echo "✨ Features: Service Layer, Repository Pattern, DTOs, Actions, Caching, Events\n";
