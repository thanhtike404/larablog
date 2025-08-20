<div>
    <ul class="mt-4">
        @forelse($users as $user)
        <li class="py-1 border-b border-gray-200 mb-2">
            <div class="flex justify-between items-center">
                <div>
                    <strong>{{ $user->name ?? 'No name' }}</strong><br>
                    <span class="text-gray-600">{{ $user->email ?? 'No Email' }}</span>
                </div>
                <button
                    wire:click="deleteUser({{ $user->id }})"
                    wire:confirm="Are you sure you want to delete this user?"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                    Delete
                </button>
            </div>
        </li>
        @empty
        <li class="py-1 text-gray-500">No users found.</li>
        @endforelse
    </ul>

    <!-- Pagination links -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>