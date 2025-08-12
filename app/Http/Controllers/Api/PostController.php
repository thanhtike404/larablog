<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of blog posts
     */
    public function index(Request $request)
    {
        $query = BlogPost::with(['user', 'category', 'tags'])
            ->where('is_published', true)
            ->orderBy('published_at', 'desc');

        // Filter by category if provided
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(10);

        return response()->json($posts);
    }

    /**
     * Show a single blog post
     */
    public function show($slug)
    {
        $post = BlogPost::with(['user', 'category', 'tags'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Increment views
        $post->incrementViews();

        return response()->json([
            'post' => $post,
            'content_blocks' => $post->getOrderedContentBlocks()
        ]);
    }

    /**
     * Store a new blog post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|string',
            'content_blocks' => 'required|array',
            'content_blocks.blocks' => 'required|array',
            'content_blocks.blocks.*.type' => 'required|string|in:paragraph,heading,image,image_gallery,quote,code,list',
            'content_blocks.blocks.*.data' => 'required|array',
            'content_blocks.blocks.*.order' => 'required|integer',
            'seo_meta' => 'nullable|array',
            'category_id' => 'required|exists:categories,id',
            'is_published' => 'boolean'
        ]);

        // Generate slug from title
        $validated['slug'] = Str::slug($validated['title']);

        // Ensure slug is unique
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (BlogPost::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $validated['user_id'] = auth()->id() ?? 1; // Default to user 1 for testing

        if ($validated['is_published'] ?? false) {
            $validated['published_at'] = now();
        }

        $post = BlogPost::create($validated);

        // Calculate reading time
        $post->calculateReadingTime();
        $post->save();

        return response()->json($post->load(['user', 'category']), 201);
    }

    /**
     * Update a blog post
     */
    public function update(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|string',
            'content_blocks' => 'sometimes|required|array',
            'content_blocks.blocks' => 'sometimes|required|array',
            'content_blocks.blocks.*.type' => 'sometimes|required|string|in:paragraph,heading,image,image_gallery,quote,code,list',
            'content_blocks.blocks.*.data' => 'sometimes|required|array',
            'content_blocks.blocks.*.order' => 'sometimes|required|integer',
            'seo_meta' => 'nullable|array',
            'category_id' => 'sometimes|required|exists:categories,id',
            'is_published' => 'boolean'
        ]);

        // Update slug if title changed
        if (isset($validated['title']) && $validated['title'] !== $post->title) {
            $newSlug = Str::slug($validated['title']);
            $originalSlug = $newSlug;
            $counter = 1;
            while (BlogPost::where('slug', $newSlug)->where('id', '!=', $post->id)->exists()) {
                $newSlug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $newSlug;
        }

        // Set published_at if publishing for the first time
        if (($validated['is_published'] ?? false) && !$post->is_published) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        // Recalculate reading time if content changed
        if (isset($validated['content_blocks'])) {
            $post->calculateReadingTime();
            $post->save();
        }

        return response()->json($post->load(['user', 'category']));
    }

    /**
     * Delete a blog post
     */
    public function destroy(BlogPost $post)
    {
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }

    /**
     * Get content blocks for a specific post (useful for editing)
     */
    public function getContentBlocks(BlogPost $post)
    {
        return response()->json([
            'content_blocks' => $post->content_blocks,
            'ordered_blocks' => $post->getOrderedContentBlocks()
        ]);
    }

    /**
     * Update only content blocks for a post
     */
    public function updateContentBlocks(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'content_blocks' => 'required|array',
            'content_blocks.blocks' => 'required|array',
            'content_blocks.blocks.*.type' => 'required|string|in:paragraph,heading,image,image_gallery,quote,code,list',
            'content_blocks.blocks.*.data' => 'required|array',
            'content_blocks.blocks.*.order' => 'required|integer',
        ]);

        $post->update(['content_blocks' => $validated['content_blocks']]);

        // Recalculate reading time
        $post->calculateReadingTime();
        $post->save();

        return response()->json([
            'message' => 'Content blocks updated successfully',
            'content_blocks' => $post->getOrderedContentBlocks()
        ]);
    }
}
