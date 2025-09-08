<?php

namespace App\Repositories;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogPostRepository
{
    public function findBySlug(string $slug): ?BlogPost
    {
        return BlogPost::where('slug', $slug)
            ->with(['tags', 'category', 'user'])
            ->first();
    }

    public function getPublished(int $perPage = 15): LengthAwarePaginator
    {
        return BlogPost::where('is_published', true)
            ->with(['tags', 'category', 'user'])
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    public function getFeatured(int $limit = 5): Collection
    {
        return BlogPost::where('is_published', true)
            ->where('is_featured', true)
            ->with(['tags', 'category', 'user'])
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getByAuthor(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return BlogPost::where('user_id', $userId)
            ->where('is_published', true)
            ->with(['tags', 'category'])
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    public function getRelated(BlogPost $post, int $limit = 3): Collection
    {
        return BlogPost::where('id', '!=', $post->id)
            ->where('is_published', true)
            ->where(function ($query) use ($post) {
                $query->where('category_id', $post->category_id)
                    ->orWhereHas('tags', function ($q) use ($post) {
                        $q->whereIn('tags.id', $post->tags->pluck('id'));
                    });
            })
            ->with(['tags', 'category', 'user'])
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getMostViewed(int $limit = 5): Collection
    {
        return BlogPost::where('is_published', true)
            ->orderBy('views_count', 'desc')
            ->with(['tags', 'category', 'user'])
            ->limit($limit)
            ->get();
    }
}
