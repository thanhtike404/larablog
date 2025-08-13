<x-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">

                <h1 class="text-3xl font-bold text-gray-900">Blog Posts</h1>
                <p class="mt-2 text-gray-600">Discover our latest articles and insights</p>
            </div>


            <!-- Two Column Layout using CSS Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                <!-- Left Column: Blog Posts (3/4 width) -->


                <livewire:posts.post-list />



                <!-- Right Column: Sidebar -->
                <livewire:posts.filter.post-filters lazy>

            </div>
        </div>
    </div>
</x-layout>