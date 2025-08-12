<nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo/Brand -->
            <div class="flex items-center">
                <a href="/" class="text-xl font-bold text-gray-800 hover:text-blue-600">
                    BlogSpace
                </a>
            </div>

            <!-- Navigation Links -->
            <ul class="hidden md:flex space-x-6 list-none">

                <x-nav-link href="home">Home</x-nav-link>

                <x-nav-link href="posts">Posts</x-nav-link>


                <x-nav-link href="about">About</x-nav-link>

                <x-nav-link href="contact">Contact</x-nav-link>
            </ul>

            <!-- Search -->
            <!-- <div class="hidden md:block">
                <input type="text" 
                       placeholder="Search..." 
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div> -->

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobile-menu-btn" class="text-gray-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden pb-4">
            <div class="space-y-2">
                <a href="/" class="block py-2 text-gray-700 hover:text-blue-600">Home</a>
                <a href="/posts" class="block py-2 text-gray-700 hover:text-blue-600">Posts</a>
                <a href="/categories" class="block py-2 text-gray-700 hover:text-blue-600">Categories</a>
                <a href="/about" class="block py-2 text-gray-700 hover:text-blue-600">About</a>
                <a href="/contact" class="block py-2 text-gray-700 hover:text-blue-600">Contact</a>

                <div class="pt-4">
                    <input type="text"
                        placeholder="Search..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>