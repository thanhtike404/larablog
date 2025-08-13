<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Popular Tags</h2>
    <div class="flex flex-wrap gap-2">
        @foreach ($tags as $tag)
        <button type="button"
            wire:click="selectTag('{{ $tag->slug }}')"
            class="px-2 py-1 text-sm rounded transition-colors {{ $currentTag == $tag->slug ? 'bg-blue-100 text-blue-800 font-medium' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            {{ $tag->name }} ({{ $tag->posts_count }})
        </button>
        @endforeach
    </div>
</div>