<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Publishable
{
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('is_published', false);
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where('published_at', '>', now());
    }

    public function publish(): bool
    {
        return $this->update([
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    public function unpublish(): bool
    {
        return $this->update([
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    public function isPublished(): bool
    {
        return $this->is_published &&
            $this->published_at &&
            $this->published_at->isPast();
    }

    public function isDraft(): bool
    {
        return !$this->is_published;
    }

    public function isScheduled(): bool
    {
        return $this->is_published &&
            $this->published_at &&
            $this->published_at->isFuture();
    }
}
