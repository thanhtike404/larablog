<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Top Authors</h2>
    <div class="space-y-3">
        @foreach ($users->take($limit) as $user)
        <button type="button"
            wire:click="selectAuthor({{ $user->id }})"
            class="w-full flex items-center p-2 rounded hover:bg-gray-50 transition-colors text-left {{ $currentAuthor == $user->id ? 'bg-blue-50' : '' }}">
            <img src="https://images.unsplash.com/photo-1503023345310-bd7c1de61c7d"
                alt="{{ $user->name }}"
                class="w-8 h-8 rounded-full mr-3">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                <p class="text-xs text-gray-500">{{ $user->blog_posts_count }} posts</p>
            </div>
        </button>
        @endforeach
    </div>
</div>