<div>
    @if($currentSearch || $currentCategory || $currentTag || $currentAuthor)
    <div class="bg-blue-50 rounded-lg border border-blue-200 p-4">
        <h3 class="text-sm font-semibold text-blue-900 mb-3">Active Filters</h3>
        <div class="space-y-2">
            @if($currentSearch)
            <div class="flex items-center justify-between bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded">
                <span>Search: "{{ $currentSearch }}"</span>
                <button type="button" wire:click="removeFilter('search')" class="text-blue-600 hover:text-blue-800 ml-2">×</button>
            </div>
            @endif

            @if($currentCategory)
            @php $selectedCategory = $categories->firstWhere('slug', $currentCategory) @endphp
            @if($selectedCategory)
            <div class="flex items-center justify-between bg-green-100 text-green-800 text-sm px-3 py-1 rounded">
                <span>Category: {{ $selectedCategory->name }}</span>
                <button type="button" wire:click="removeFilter('category')" class="text-green-600 hover:text-green-800 ml-2">×</button>
            </div>
            @endif
            @endif

            @if($currentTag)
            @php $selectedTag = $tags->firstWhere('slug', $currentTag) @endphp
            @if($selectedTag)
            <div class="flex items-center justify-between bg-purple-100 text-purple-800 text-sm px-3 py-1 rounded">
                <span>Tag: {{ $selectedTag->name }}</span>
                <button type="button" wire:click="removeFilter('tag')" class="text-purple-600 hover:text-purple-800 ml-2">×</button>
            </div>
            @endif
            @endif

            @if($currentAuthor)
            @php $selectedAuthor = $users->firstWhere('id', $currentAuthor) @endphp
            @if($selectedAuthor)
            <div class="flex items-center justify-between bg-orange-100 text-orange-800 text-sm px-3 py-1 rounded">
                <span>Author: {{ $selectedAuthor->name }}</span>
                <button type="button" wire:click="removeFilter('author')" class="text-orange-600 hover:text-orange-800 ml-2">×</button>
            </div>
            @endif
            @endif
        </div>
    </div>
    @endif
</div>