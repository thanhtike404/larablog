<div class="mt-6">
    <h3 class="text-lg font-semibold mb-4">Blog Posts</h3>

    <!-- Add this test button -->
    <button wire:click="testFetch" class="bg-red-500 text-white px-4 py-2 rounded mb-4">
        Test Fetch Posts
    </button>

    <!-- Rest of your existing code -->
    @if(count($posts) > 0)
    <div class="space-y-4">
        @foreach($posts as $post)
        <div class="border p-4 rounded">
            <h4 class="font-bold">{{ $post->title ?? 'No Title' }}</h4>
            <p class="text-gray-600">{{ $post->content ?? 'No Content' }}</p>
            <small class="text-gray-500">{{ $post->created_at->diffForHumans() }}</small>
        </div>
        @endforeach
    </div>
    @else
    <p class="text-gray-500">No posts available. Posts count: {{ count($posts) }}</p>
    @endif
    {{
        $posts->links()  # This will render the pagination links
    }}
</div>