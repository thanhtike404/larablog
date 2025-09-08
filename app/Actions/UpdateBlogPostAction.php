<?php

namespace App\Actions;

use App\DTOs\BlogPostData;
use App\Models\BlogPost;
use Illuminate\Support\Facades\DB;

class UpdateBlogPostAction
{
    public function execute(BlogPost $post, BlogPostData $data): BlogPost
    {
        return DB::transaction(function () use ($post, $data) {
            $post->update($data->toArray());

            if (!empty($data->tagIds)) {
                $post->tags()->sync($data->tagIds);
            }

            if ($post->wasChanged(['title', 'content_blocks'])) {
                $post->calculateReadingTime();
                $post->save();
            }

            return $post->load(['tags', 'category', 'user']);
        });
    }
}
