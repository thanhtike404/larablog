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

// Test Redis route
Route::get('/test-redis', function () {
    try {
        // Test basic Redis operations
        $testKey = 'test_key';
        $testValue = 'test_value';

        // Set a value
        $setResult = \Illuminate\Support\Facades\Redis::set($testKey, $testValue);

        // Get the value
        $getValue = \Illuminate\Support\Facades\Redis::get($testKey);

        // Get all keys to see what's actually stored
        $allKeys = \Illuminate\Support\Facades\Redis::keys('*');

        // Get Redis prefix from config
        $prefix = config('database.redis.options.prefix', '');

        return response()->json([
            'set_result' => $setResult,
            'get_result' => $getValue,
            'expected_value' => $testValue,
            'match' => $getValue === $testValue,
            'redis_prefix' => $prefix,
            'all_keys' => $allKeys,
            'raw_connection_test' => 'Redis is working!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'redis_config' => config('database.redis')
        ]);
    }
});
