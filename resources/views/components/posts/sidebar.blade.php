@props(['categories', 'tags', 'users'])

<div class="lg:col-span-1">
    <div class="space-y-6">
        <!-- Search Bar -->
        <x-posts.search-bar />

        <!-- Active Filters -->
        <x-posts.active-filters :categories="$categories" :tags="$tags" :users="$users" />

        <!-- Categories -->
        <x-posts.categories-filter :categories="$categories" />

        <!-- Tags -->
        <x-posts.tags-filter :tags="$tags" />

        <!-- Authors -->
        <x-posts.authors-filter :users="$users" />
    </div>
</div>