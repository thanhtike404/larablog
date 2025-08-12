@php
$seoTitle = $post->seo_meta['title'] ?? $post->title;
$seoDescription = $post->seo_meta['description'] ?? $post->excerpt ?? Str::limit($post->getPlainTextContent(), 160);
@endphp

<x-layout :title="$seoTitle">
    @push('meta')
    <meta name="description" content="{{ $seoDescription }}">
    @if(isset($post->seo_meta['keywords']))
    <meta name="keywords" content="{{ implode(', ', $post->seo_meta['keywords']) }}">
    @endif
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($post->featured_image)
    <meta property="og:image" content="{{ asset($post->featured_image) }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    @if($post->featured_image)
    <meta name="twitter:image" content="{{ asset($post->featured_image) }}">
    @endif
    <meta name="article:published_time" content="{{ $post->published_at->toISOString() }}">
    <meta name="article:modified_time" content="{{ $post->updated_at->toISOString() }}">
    <meta name="article:author" content="{{ $post->user->name }}">
    <meta name="article:section" content="{{ $post->category->name }}">
    @foreach($post->tags as $tag)
    <meta name="article:tag" content="{{ $tag->name }}">
    @endforeach
    @endpush
    <div class="max-w-4xl mx-auto">
        <!-- Post Header -->
        <article class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Featured Image -->
            @if($post->featured_image)
            <div class="w-full h-64 md:h-96 overflow-hidden">
                <img src="{{ $post->featured_image }}"
                    alt="{{ $post->title }}"
                    class="w-full h-full object-cover">
            </div>
            @endif

            <!-- Post Content -->
            <div class="p-6 md:p-8">
                <!-- Post Meta -->
                <div class="flex flex-wrap items-center gap-4 mb-6 text-sm text-gray-600">
                    <!-- Author -->
                    <div class="flex items-center">
                        <img src="https://images.unsplash.com/photo-1503023345310-bd7c1de61c7d"
                            alt="{{ $post->user->name }}"
                            class="w-8 h-8 rounded-full mr-2">
                        <span class="font-medium">{{ $post->user->name }}</span>
                    </div>

                    <!-- Published Date -->
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $post->published_at->format('M d, Y') }}
                    </div>

                    <!-- Reading Time -->
                    @if($post->reading_time)
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $post->reading_time }} min read
                    </div>
                    @endif

                    <!-- Views -->
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                        </svg>
                        {{ number_format($post->views_count) }} views
                    </div>
                </div>

                <!-- Title -->
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 leading-tight">
                    {{ $post->title }}
                </h1>

                <!-- Excerpt -->
                @if($post->excerpt)
                <p class="text-xl text-gray-600 mb-6 leading-relaxed">
                    {{ $post->excerpt }}
                </p>
                @endif

                <!-- Category and Tags -->
                <div class="flex flex-wrap gap-2 mb-8">
                    <!-- Category -->
                    <span class="inline-block bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                        {{ $post->category->name }}
                    </span>

                    <!-- Tags -->
                    @foreach($post->tags as $tag)
                    <span class="inline-block bg-gray-100 text-gray-700 text-sm font-medium px-3 py-1 rounded-full">
                        {{ $tag->name }}
                    </span>
                    @endforeach
                </div>

                <!-- Content Blocks -->
                <div class="prose prose-lg max-w-none">
                    @foreach($contentBlocks as $block)
                    <x-content-block :block="$block" />
                    @endforeach
                </div>
            </div>
        </article>

        <!-- Related Posts or Navigation -->
        <div class="mt-12">
            <div class="flex justify-between items-center">
                <a href="{{ route('posts') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                    </svg>
                    Back to Posts
                </a>

                <!-- Share buttons could go here -->
                <div class="flex space-x-2">
                    <button class="p-2 text-gray-600 hover:text-blue-600 transition-colors" title="Share on Twitter">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"></path>
                        </svg>
                    </button>
                    <button class="p-2 text-gray-600 hover:text-blue-600 transition-colors" title="Share on Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M20 10C20 4.477 15.523 0 10 0S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layout>