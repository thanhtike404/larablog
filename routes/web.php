<?php

use App\Livewire\Posts\CreateBlogPost;
use App\Livewire\Posts\PostList;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;

Route::view('/', 'welcome');

Route::group(['prefix' => 'dashboard'], function () {
    Route::get('/', DashboardController::class . '@index')
        ->middleware(['auth', 'verified'])
        ->name('dashboard');
    Route::get('/createdPosts', DashboardController::class . '@postList')
        ->middleware(['auth', 'verified'])
        ->name('dashboard.createdPosts');
});


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';

Route::get('posts', PostList::class)->name('posts.show');
Route::get('/dashboard/create-blog-post', CreateBlogPost::class)->name('dashboard.posts.create');
