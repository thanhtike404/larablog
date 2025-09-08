<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Services\BlogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct(
        private readonly BlogService $blogService
    ) {}

    public function index(): JsonResponse
    {
        $tags = Tag::withCount('posts')
            ->orderBy('name')
            ->get();

        return response()->json($tags);
    }

    public function popular(): JsonResponse
    {
        $tags = $this->blogService->getPopularTags(20);

        return response()->json($tags);
    }

    public function show(Tag $tag): JsonResponse
    {
        $tag->load(['publishedPosts' => function ($query) {
            $query->with(['category', 'user'])->latest('published_at')->limit(5);
        }]);

        return response()->json($tag);
    }

    public function posts(Request $request, Tag $tag): JsonResponse
    {
        $perPage = $request->integer('per_page', 15);
        $posts = $this->blogService->getPostsByTag($tag->slug, $perPage);

        return response()->json($posts);
    }
}
