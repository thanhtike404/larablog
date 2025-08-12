@props(['route' => 'posts'])

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Search Posts</h2>
    <form method="GET" action="{{ route($route) }}">
        <!-- Preserve existing filters -->
        @if(request('category'))
        <input type="hidden" name="category" value="{{ request('category') }}">
        @endif
        @if(request('tag'))
        <input type="hidden" name="tag" value="{{ request('tag') }}">
        @endif
        @if(request('author'))
        <input type="hidden" name="author" value="{{ request('author') }}">
        @endif

        <div class="mb-4">
            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search posts..."
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                Search
            </button>
            @if(request()->hasAny(['search', 'category', 'tag', 'author']))
            <a href="{{ route($route) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                Clear
            </a>
            @endif
        </div>
    </form>
</div>