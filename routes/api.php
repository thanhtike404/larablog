<?php

use App\Http\Controllers\Api\BlogPostController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;

// Blog API Routes
Route::prefix('blog')->group(function () {
    // Blog Posts
    Route::prefix('posts')->group(function () {
        Route::get('/', [BlogPostController::class, 'index'])->name('api.posts.index');
        Route::get('/featured', [BlogPostController::class, 'featured'])->name('api.posts.featured');
        Route::get('/popular', [BlogPostController::class, 'popular'])->name('api.posts.popular');
        Route::get('/{post:slug}', [BlogPostController::class, 'show'])->name('api.posts.show');

        // Protected routes (require authentication)
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/', [BlogPostController::class, 'store'])->name('api.posts.store');
            Route::put('/{post}', [BlogPostController::class, 'update'])->name('api.posts.update');
            Route::delete('/{post}', [BlogPostController::class, 'destroy'])->name('api.posts.destroy');
        });
    });

    // Categories
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('api.categories.index');
        Route::get('/{category:slug}', [CategoryController::class, 'show'])->name('api.categories.show');
        Route::get('/{category:slug}/posts', [CategoryController::class, 'posts'])->name('api.categories.posts');
    });

    // Tags
    Route::prefix('tags')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('api.tags.index');
        Route::get('/popular', [TagController::class, 'popular'])->name('api.tags.popular');
        Route::get('/{tag:slug}', [TagController::class, 'show'])->name('api.tags.show');
        Route::get('/{tag:slug}/posts', [TagController::class, 'posts'])->name('api.tags.posts');
    });
});
