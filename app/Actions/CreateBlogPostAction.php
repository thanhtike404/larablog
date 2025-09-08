<?php

namespace App\Actions;

use App\DTOs\BlogPostData;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateBlogPostAction
{
    public function execute(BlogPostData $data, User $author): BlogPost
    {
        return DB::transaction(function () use ($data, $author) {
            $post = BlogPost::create([
                ...$data->toArray(),
                'user_id' => $author->id,
            ]);

            if (!empty($data->tagIds)) {
                $post->tags()->sync($data->tagIds);
            }

            $post->calculateReadingTime();
            $post->save();

            return $post->load(['tags', 'category', 'user']);
        });
    }
}
