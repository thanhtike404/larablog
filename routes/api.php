<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

// Blog Post API Routes
Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('api.posts.index');
    Route::get('/{slug}', [PostController::class, 'show'])->name('api.posts.show');
    Route::post('/', [PostController::class, 'store'])->name('api.posts.store');
    Route::put('/{post}', [PostController::class, 'update'])->name('api.posts.update');
    Route::delete('/{post}', [PostController::class, 'destroy'])->name('api.posts.destroy');
    Route::get('/{post}/content-blocks', [PostController::class, 'getContentBlocks'])->name('api.posts.content-blocks');
    Route::put('/{post}/content-blocks', [PostController::class, 'updateContentBlocks'])->name('api.posts.update-content-blocks');
});
