<?php

use App\Http\Controllers\PostController;
use App\Livewire\AuthorsPage;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;


Route::get('/', function () {
    return view('home');
})->name('home');
Route::get('/users', function () {
    $users = User::all();
    return view('users', ['users' => $users]);
})->name('users');

Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::get('/categories', function () {
    return view('categories');
})->name('categories');

// Web routes for blog posts

Route::prefix('posts')->group(function () {
    Route::get('/', function () {
        return view('posts');
    })->name('posts');
    Route::get('/{slug}', [PostController::class, 'show'])->name('posts.show');
});

Route::get('/authors', AuthorsPage::class)->name('authors');
