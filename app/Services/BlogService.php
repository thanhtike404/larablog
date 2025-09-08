<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogService
{
    public function createPost(array $data, User $author): BlogPost
    {
        $post = BlogPost::create([
            ...$data,
            'user_id' => $author->id,
            'slug' => $this->generateUniqueSlug($data['title']),
        ]);

        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        $post->calculateReadingTime();
        $post->save();

        return $post->load(['tags', 'category', 'user']);
    }

    public function updatePost(BlogPost $post, array $data): BlogPost
    {
        $post->update($data);

        if (isset($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        if (isset($data['title']) || isset($data['content_blocks'])) {
            $post->calculateReadingTime();
            $post->save();
        }

        return $post->load(['tags', 'category', 'user']);
    }

    public function getPublishedPosts(int $perPage = 15): LengthAwarePaginator
    {
        return BlogPost::where('is_published', true)
            ->with(['tags', 'category', 'user'])
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    public function getPostsByTag(string $tagSlug, int $perPage = 15): LengthAwarePaginator
    {
        return BlogPost::whereHas('tags', function ($query) use ($tagSlug) {
            $query->where('slug', $tagSlug);
        })
            ->where('is_published', true)
            ->with(['tags', 'category', 'user'])
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    public function getPostsByCategory(string $categorySlug, int $perPage = 15): LengthAwarePaginator
    {
        return BlogPost::whereHas('category', function ($query) use ($categorySlug) {
            $query->where('slug', $categorySlug);
        })
            ->where('is_published', true)
            ->with(['tags', 'category', 'user'])
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    public function searchPosts(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return BlogPost::where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->with(['tags', 'category', 'user'])
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    public function getPopularTags(int $limit = 10): Collection
    {
        return Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit($limit)
            ->get();
    }

    public function incrementPostViews(BlogPost $post): void
    {
        $post->incrementViews();
    }

    private function generateUniqueSlug(string $title): string
    {
        $slug = str($title)->slug();
        $originalSlug = $slug;
        $counter = 1;

        while (BlogPost::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
