@props(['categories', 'tags', 'users'])

@if(request()->hasAny(['search', 'category', 'tag', 'author']))
<div class="bg-blue-50 rounded-lg border border-blue-200 p-4">
    <h3 class="text-sm font-semibold text-blue-900 mb-3">Active Filters</h3>
    <div class="space-y-2">
        @if(request('search'))
        <div class="flex items-center justify-between bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded">
            <span>Search: "{{ request('search') }}"</span>
            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="text-blue-600 hover:text-blue-800 ml-2">×</a>
        </div>
        @endif

        @if(request('category'))
        @php $currentCategory = $categories->firstWhere('slug', request('category')) @endphp
        @if($currentCategory)
        <div class="flex items-center justify-between bg-green-100 text-green-800 text-sm px-3 py-1 rounded">
            <span>Category: {{ $currentCategory->name }}</span>
            <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="text-green-600 hover:text-green-800 ml-2">×</a>
        </div>
        @endif
        @endif

        @if(request('tag'))
        @php $currentTag = $tags->firstWhere('slug', request('tag')) @endphp
        @if($currentTag)
        <div class="flex items-center justify-between bg-purple-100 text-purple-800 text-sm px-3 py-1 rounded">
            <span>Tag: {{ $currentTag->name }}</span>
            <a href="{{ request()->fullUrlWithQuery(['tag' => null]) }}" class="text-purple-600 hover:text-purple-800 ml-2">×</a>
        </div>
        @endif
        @endif

        @if(request('author'))
        @php $currentAuthor = $users->firstWhere('id', request('author')) @endphp
        @if($currentAuthor)
        <div class="flex items-center justify-between bg-orange-100 text-orange-800 text-sm px-3 py-1 rounded">
            <span>Author: {{ $currentAuthor->name }}</span>
            <a href="{{ request()->fullUrlWithQuery(['author' => null]) }}" class="text-orange-600 hover:text-orange-800 ml-2">×</a>
        </div>
        @endif
        @endif
    </div>
</div>
@endif