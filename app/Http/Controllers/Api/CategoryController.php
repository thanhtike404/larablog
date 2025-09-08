<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\BlogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private readonly BlogService $blogService
    ) {}

    public function index(): JsonResponse
    {
        $categories = Category::withCount('publishedPosts')
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }

    public function show(Category $category): JsonResponse
    {
        $category->load(['publishedPosts' => function ($query) {
            $query->with(['tags', 'user'])->latest('published_at')->limit(5);
        }]);

        return response()->json($category);
    }

    public function posts(Request $request, Category $category): JsonResponse
    {
        $perPage = $request->integer('per_page', 15);
        $posts = $this->blogService->getPostsByCategory($category->slug, $perPage);

        return response()->json($posts);
    }
}
