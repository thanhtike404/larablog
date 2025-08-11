<?php

// Example usage of your blog system with tags and categories

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;

// Create a sample blog post
$user = User::first();
$category = Category::where('slug', 'backend-development')->first();

$post = BlogPost::create([
    'title' => 'Building a REST API with Laravel and Docker',
    'slug' => 'building-rest-api-laravel-docker',
    'content' => 'In this tutorial, we will explore how to build a robust REST API using Laravel framework and containerize it with Docker...',
    'is_published' => true,
    'published_at' => now(),
    'user_id' => $user->id,
    'category_id' => $category->id
]);

// Attach tags (libraries/technologies used in the article)
$laravelTag = Tag::where('slug', 'laravel')->first();
$dockerTag = Tag::where('slug', 'docker')->first();

$post->tags()->attach([$laravelTag->id, $dockerTag->id]);

// Query examples:

// 1. Get all posts with their tags
$postsWithTags = BlogPost::with('tags')->get();

// 2. Get all posts that use Laravel
$laravelPosts = BlogPost::whereHas('tags', function ($query) {
    $query->where('slug', 'laravel');
})->get();

// 3. Get all tags used in published posts
$tagsInPublishedPosts = Tag::whereHas('posts', function ($query) {
    $query->where('is_published', true);
})->get();

// 4. Get posts by category with their tags
$backendPosts = BlogPost::where('category_id', $category->id)
    ->with(['tags', 'category', 'user'])
    ->get();

// 5. Get most popular tags (used in most posts)
$popularTags = Tag::withCount('posts')
    ->orderBy('posts_count', 'desc')
    ->take(10)
    ->get();

echo "Blog system is ready to use!\n";
echo "You can now create posts, assign categories, and tag them with technologies used.\n";
