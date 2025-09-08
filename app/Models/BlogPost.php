<?php

namespace App\Models;

use App\Models\Traits\HasSlug;
use App\Models\Traits\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogPost extends Model
{
    use HasFactory, HasSlug, Publishable;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'content_blocks',
        'seo_meta',
        'is_published',
        'published_at',
        'user_id',
        'category_id',
        'reading_time',
        'views_count',
        'is_featured'
    ];

    protected $casts = [
        'content_blocks' => 'array',
        'seo_meta' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'is_featured' => 'boolean'
    ];

    /**
     * Get the user that owns the blog post
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the blog post
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the categories for the blog post (many-to-many)
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'post_category', 'post_id', 'category_id');
    }

    /**
     * Get the tags (libraries/technologies) for the blog post
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id')
            ->withTimestamps();
    }

    /**
     * Get content blocks sorted by order
     */
    public function getOrderedContentBlocks()
    {
        if (!$this->content_blocks || !isset($this->content_blocks['blocks'])) {
            return [];
        }

        $blocks = $this->content_blocks['blocks'];
        usort($blocks, function ($a, $b) {
            return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
        });

        return $blocks;
    }

    /**
     * Get plain text content from all text blocks for search/preview
     */
    public function getPlainTextContent()
    {
        $blocks = $this->getOrderedContentBlocks();
        $textContent = [];

        foreach ($blocks as $block) {
            if (in_array($block['type'], ['paragraph', 'heading', 'quote'])) {
                $textContent[] = strip_tags($block['data']['text'] ?? '');
            }
        }

        return implode(' ', $textContent);
    }

    /**
     * Calculate and update reading time based on content
     */
    public function calculateReadingTime()
    {
        $plainText = $this->getPlainTextContent();
        $wordCount = str_word_count($plainText);
        $readingTime = max(1, ceil($wordCount / 200)); // 200 words per minute

        $this->reading_time = $readingTime;
        return $readingTime;
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }
}
