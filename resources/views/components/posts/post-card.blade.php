@props(['post'])

<article class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 overflow-hidden hover:shadow-md transition-shadow">
    <div class="p-6">
        <!-- Author info -->
        <div class="flex items-center mb-4">
            <img src="https://images.unsplash.com/photo-1503023345310-bd7c1de61c7d"
                alt="{{ $post->user->name }}"
                class="w-10 h-10 rounded-full mr-3">
            <div>
                <p class="text-sm font-medium text-gray-900">{{ $post->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $post->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        <!-- Post Content -->
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Content -->
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-900 mb-3">
                    <a href="{{ route('posts.show', $post->slug) }}" class="hover:text-blue-600 transition-colors">
                        {{ $post->title }}
                    </a>
                </h2>

                <!-- Category and Tags -->
                <div class="flex flex-wrap gap-2 mb-3">
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium">
                        {{ $post->category->name }}
                    </span>
                    @foreach ($post->tags->take(3) as $tag)
                    <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">
                        {{ $tag->name }}
                    </span>
                    @endforeach
                </div>

                <!-- Excerpt -->
                @if($post->excerpt)
                <p class="text-gray-600 mb-4 leading-relaxed">{{ $post->excerpt }}</p>
                @else
                <p class="text-gray-600 mb-4 leading-relaxed">{{ Str::limit($post->getPlainTextContent(), 200) }}</p>
                @endif

                <!-- Post Stats -->
                <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                    @if($post->reading_time)
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $post->reading_time }} min read
                    </span>
                    @endif
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                        </svg>
                        {{ number_format($post->views_count) }} views
                    </span>
                </div>

                <!-- Read More -->
                <a href="{{ route('posts.show', $post->slug) }}"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors">
                    Read full article
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- Featured Image -->
            @if($post->featured_image)
            <div class="md:w-48 flex-shrink-0">
                <img src="{{ $post->featured_image }}"
                    alt="{{ $post->title }}"
                    class="w-full h-32 object-cover rounded-lg">
            </div>
            @endif
        </div>
    </div>
</article>