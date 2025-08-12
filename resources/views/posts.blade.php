<x-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Blog Posts</h1>
                <p class="mt-2 text-gray-600">Discover our latest articles and insights</p>
            </div>

            <!-- Two Column Layout using CSS Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Left Column: Blog Posts (3/4 width) -->
                <div class="lg:col-span-3">
                    @forelse ($posts as $post)
                    <x-posts.post-card :post="$post" />
                    @empty
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No posts found</h3>
                        <p class="text-gray-500">Try adjusting your search or filter criteria.</p>
                        @if(request()->hasAny(['search', 'category', 'tag', 'author']))
                        <a href="{{ route('posts') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Clear all filters
                        </a>
                        @endif
                    </div>
                    @endforelse

                    <!-- Pagination -->
                    @if($posts->hasPages())
                    <div class="mt-8">
                        {{ $posts->links() }}
                    </div>
                    @endif
                </div>

                <!-- Right Column: Sidebar -->
                <x-posts.sidebar :categories="$categories" :tags="$tags" :users="$users" />
            </div>
        </div>
    </div>
</x-layout>