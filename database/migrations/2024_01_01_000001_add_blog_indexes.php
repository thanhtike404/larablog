<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            // Add is_featured column if it doesn't exist
            if (!Schema::hasColumn('blog_posts', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_published');
            }
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            // Add indexes for better query performance
            $table->index(['is_published', 'published_at']);
            $table->index(['category_id', 'is_published']);
            $table->index(['user_id', 'is_published']);
            $table->index('views_count');
            $table->index('is_featured');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('slug');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->index('slug');
        });

        // Add indexes to pivot tables
        Schema::table('post_tag', function (Blueprint $table) {
            $table->index(['post_id', 'tag_id']);
            $table->index(['tag_id', 'post_id']);
        });

        if (Schema::hasTable('post_category')) {
            Schema::table('post_category', function (Blueprint $table) {
                $table->index(['post_id', 'category_id']);
                $table->index(['category_id', 'post_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropIndex(['is_published', 'published_at']);
            $table->dropIndex(['category_id', 'is_published']);
            $table->dropIndex(['user_id', 'is_published']);
            $table->dropIndex(['views_count']);
            $table->dropIndex(['is_featured']);

            if (Schema::hasColumn('blog_posts', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['slug']);
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropIndex(['slug']);
        });

        Schema::table('post_tag', function (Blueprint $table) {
            $table->dropIndex(['post_id', 'tag_id']);
            $table->dropIndex(['tag_id', 'post_id']);
        });

        if (Schema::hasTable('post_category')) {
            Schema::table('post_category', function (Blueprint $table) {
                $table->dropIndex(['post_id', 'category_id']);
                $table->dropIndex(['category_id', 'post_id']);
            });
        }
    }
};
