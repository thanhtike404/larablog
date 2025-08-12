<x-layout>
    <div class="max-w-5xl mx-auto px-3 md:px-4 py-6 md:py-8">
        <h1 class="text-2xl md:text-3xl font-bold mb-4 md:mb-6">Blog Posts</h1>

        <div class="bg-white rounded-lg  shadow-md overflow-hidden mb-6 border border-gray-200">
            @forelse ($posts as $post)
            <div class="p-4 md:p-6 my-4 border-b border-gray-200 mb-4 last:border-b-0">
                <!-- Author info -->
                <div class="flex items-center mb-3 md:mb-4">
                    <img
                        src="{{ optional($post->user)->profile_photo_url ?: 'https://images.unsplash.com/photo-1503023345310-bd7c1de61c7d' }}"
                        alt="{{ optional($post->user)->name ?? 'Guest User' }}"
                        class="w-8 h-8 md:w-10 md:h-10 rounded-full mr-3" />
                    <div class="flex flex-col">
                        <span class="text-gray-800 font-semibold text-sm md:text-base">{{ $post->user->name }}</span>
                        <span class="text-gray-500 text-xs md:text-sm">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <!-- Two-column layout: Content left, Image right (desktop) / Stacked (mobile) -->
                <div class="flex flex-col md:flex-row gap-4 md:gap-6 items-start">
                    <!-- Left column: Content -->
                    <div class="flex-1 min-w-0 order-2 md:order-1">
                        <h2 class="text-xl md:text-2xl font-bold mb-3 leading-tight">{{ $post->title }}</h2>

                        <!-- Category and Tags -->
                        <div class="mb-3 md:mb-4 flex flex-wrap">
                            <span class="inline-block bg-gray-100 text-gray-700 text-xs font-medium px-2 py-1 rounded mr-2 mb-1">
                                {{ $post->category->name }}
                            </span>
                            @foreach ($post->tags as $tag)
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded mr-2 mb-1">
                                {{ $tag->name }}
                            </span>
                            @endforeach
                        </div>

                        <!-- Excerpt -->
                        <p class="text-gray-600 mb-3 text-sm md:text-base leading-relaxed">{{ $post->excerpt }}</p>

                        <!-- Content preview -->
                        <div class="text-gray-700 mb-3 md:mb-4 leading-relaxed">
                            <p class="text-sm md:text-base">{{ Str::limit($post->content, 150) }}</p>
                        </div>

                        <!-- Last updated -->
                        <p class="text-gray-400 text-xs md:text-sm">Last updated: {{ $post->updated_at->diffForHumans() }}</p>

                        <!-- Read more link -->
                        <div class="mt-3 md:mt-4">
                            <a href="#" class="text-blue-600 hover:text-blue-800 font-medium text-xs md:text-sm">
                                Read more →
                            </a>
                        </div>
                    </div>

                    <!-- Right column: Image -->
                    <div class="flex-shrink-0 order-1 md:order-2 w-full md:w-auto">
                        <img
                            src="{{ $post->main_image ? $post->main_image : 'https://images.unsplash.com/photo-1503023345310-bd7c1de61c7d'}}"
                            alt="{{ $post->title }}"
                            class="w-full h-48 md:w-40 md:h-32 object-cover rounded-lg shadow-sm" />
                    </div>
                </div>
            </div>
            @empty
            <div class="p-6">
                <p class="text-gray-500 text-center">No blog posts found.</p>
            </div>
            @endforelse
        </div>
    </div>
</x-layout>