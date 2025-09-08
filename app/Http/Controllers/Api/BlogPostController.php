<?php

namespace App\Http\Controllers\Api;

use App\Actions\CreateBlogPostAction;
use App\Actions\UpdateBlogPostAction;
use App\DTOs\BlogPostData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogPostRequest;
use App\Http\Requests\UpdateBlogPostRequest;
use App\Models\BlogPost;
use App\Repositories\BlogPostRepository;
use App\Services\BlogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    public function __construct(
        private readonly BlogService $blogService,
        private readonly BlogPostRepository $repository,
        private readonly CreateBlogPostAction $createAction,
        private readonly UpdateBlogPostAction $updateAction,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 15);

        if ($request->has('search')) {
            $posts = $this->blogService->searchPosts($request->string('search'), $perPage);
        } elseif ($request->has('tag')) {
            $posts = $this->blogService->getPostsByTag($request->string('tag'), $perPage);
        } elseif ($request->has('category')) {
            $posts = $this->blogService->getPostsByCategory($request->string('category'), $perPage);
        } else {
            $posts = $this->blogService->getPublishedPosts($perPage);
        }

        return response()->json($posts);
    }

    public function show(BlogPost $post): JsonResponse
    {
        $post->load(['tags', 'category', 'user']);
        $this->blogService->incrementPostViews($post);

        return response()->json([
            'post' => $post,
            'related_posts' => $this->repository->getRelated($post, 3),
        ]);
    }

    public function store(StoreBlogPostRequest $request): JsonResponse
    {
        $data = BlogPostData::fromArray($request->validated());
        $post = $this->createAction->execute($data, $request->user());

        return response()->json($post, 201);
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $post): JsonResponse
    {
        $data = BlogPostData::fromArray($request->validated());
        $post = $this->updateAction->execute($post, $data);

        return response()->json($post);
    }

    public function destroy(BlogPost $post): JsonResponse
    {
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function featured(): JsonResponse
    {
        $posts = $this->repository->getFeatured();

        return response()->json($posts);
    }

    public function popular(): JsonResponse
    {
        $posts = $this->repository->getMostViewed();

        return response()->json($posts);
    }
}
