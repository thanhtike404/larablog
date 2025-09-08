<?php

namespace App\Models;

use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'color',
        'description',
        'official_url'
    ];

    /**
     * Get the posts that have this tag
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(BlogPost::class, 'post_tag', 'tag_id', 'post_id')
            ->withTimestamps();
    }

    /**
     * Get published posts that have this tag
     */
    public function publishedPosts(): BelongsToMany
    {
        return $this->posts()->published();
    }

    /**
     * Scope to get popular tags (with most posts)
     */
    public function scopePopular($query, int $limit = 10)
    {
        return $query->withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit($limit);
    }
}
