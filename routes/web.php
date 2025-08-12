<?php

use App\Http\Controllers\PostController;
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

// Web route for blog posts
Route::get('/posts', [PostController::class, 'index'])->name('posts');
