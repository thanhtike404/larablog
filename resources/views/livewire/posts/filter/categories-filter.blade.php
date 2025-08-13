<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Categories</h2>
    <div class="space-y-2">
        @foreach ($categories as $category)
        <button type="button"
            wire:click="selectCategory('{{ $category->slug }}')"
            class="w-full flex items-center justify-between p-2 rounded hover:bg-gray-50 transition-colors text-left {{ $currentCategory == $category->slug ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700' }}">
            <span>{{ $category->name }}</span>
            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                {{ $category->posts_count }}
            </span>
        </button>
        @endforeach
    </div>
</div>