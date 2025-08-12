<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, add new columns as nullable
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->text('excerpt')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('content_blocks')->nullable();
            $table->json('seo_meta')->nullable();
            $table->integer('reading_time')->nullable();
            $table->integer('views_count')->default(0);
        });

        // Convert existing content to JSON format
        DB::table('blog_posts')->get()->each(function ($post) {
            $contentBlocks = [
                'blocks' => [
                    [
                        'id' => 'block_1',
                        'type' => 'paragraph',
                        'data' => [
                            'text' => $post->content ?? ''
                        ],
                        'order' => 1
                    ]
                ]
            ];

            DB::table('blog_posts')
                ->where('id', $post->id)
                ->update([
                    'content_blocks' => json_encode($contentBlocks),
                    'featured_image' => $post->main_image ?? $post->image,
                    'excerpt' => Str::limit(strip_tags($post->content ?? ''), 200),
                    'reading_time' => max(1, ceil(str_word_count(strip_tags($post->content ?? '')) / 200))
                ]);
        });

        // Now make content_blocks not nullable and remove old columns
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->json('content_blocks')->nullable(false)->change();
            $table->dropColumn(['content', 'main_image', 'image']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            // Restore old columns
            $table->text('content');
            $table->text('main_image')->nullable();
            $table->string('image')->nullable();

            // Remove new columns
            $table->dropColumn(['excerpt', 'featured_image', 'content_blocks', 'seo_meta', 'reading_time', 'views_count']);
        });
    }
};
