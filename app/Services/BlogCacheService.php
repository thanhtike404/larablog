<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;

class BlogCacheService
{
    private const CACHE_TTL = 3600; // 1 hour

    public function getPopularPosts(int $limit = 5): array
    {
        return Cache::remember(
            "blog.popular_posts.{$limit}",
            self::CACHE_TTL,
            fn() => BlogPost::published()
                ->orderBy('views_count', 'desc')
                ->with(['category', 'user'])
                ->limit($limit)
                ->get()
                ->toArray()
        );
    }

    public function getFeaturedPosts(int $limit = 3): array
    {
        return Cache::remember(
            "blog.featured_posts.{$limit}",
            self::CACHE_TTL,
            fn() => BlogPost::published()
                ->where('is_featured', true)
                ->with(['category', 'user'])
                ->orderBy('published_at', 'desc')
                ->limit($limit)
                ->get()
                ->toArray()
        );
    }

    public function getPopularTags(int $limit = 10): array
    {
        return Cache::remember(
            "blog.popular_tags.{$limit}",
            self::CACHE_TTL,
            fn() => Tag::popular($limit)->get()->toArray()
        );
    }

    public function getCategories(): array
    {
        return Cache::remember(
            'blog.categories',
            self::CACHE_TTL,
            fn() => Category::withCount('publishedPosts')
                ->orderBy('name')
                ->get()
                ->toArray()
        );
    }

    public function getBlogStats(): array
    {
        return Cache::remember(
            'blog.stats',
            self::CACHE_TTL,
            fn() => [
                'total_posts' => BlogPost::published()->count(),
                'total_categories' => Category::count(),
                'total_tags' => Tag::count(),
                'total_views' => BlogPost::sum('views_count'),
            ]
        );
    }

    public function clearPostCache(BlogPost $post): void
    {
        $tags = [
            'blog.popular_posts.*',
            'blog.featured_posts.*',
            'blog.stats',
            "blog.post.{$post->slug}",
            "blog.category.{$post->category->slug}",
        ];

        foreach ($post->tags as $tag) {
            $tags[] = "blog.tag.{$tag->slug}";
        }

        Cache::tags($tags)->flush();
    }

    public function clearAllBlogCache(): void
    {
        Cache::tags(['blog'])->flush();
    }
}
