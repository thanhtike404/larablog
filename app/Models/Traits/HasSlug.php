<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            if (empty($model->slug) && !empty($model->title)) {
                $model->slug = $model->generateUniqueSlug($model->title);
            }
        });

        static::updating(function (Model $model) {
            if ($model->isDirty('title') && empty($model->slug)) {
                $model->slug = $model->generateUniqueSlug($model->title);
            }
        });
    }

    public function generateUniqueSlug(string $title): string
    {
        $slug = str($title)->slug();
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
