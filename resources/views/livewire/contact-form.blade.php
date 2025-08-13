<div>
    {{-- Do your work, then step back. --}}
    <form wire:submit.prevent="submit" class="space-y-4">
        <input type="text" wire:model.live="name" class="border p-2 w-full" placeholder="Your Name">
        <input type="email" wire:model.live="email" class="border p-2 w-full" placeholder="Your email">

        <button type="submit" wire:loading.attr="disabled" class="bg-blue-500 text-white px-4 py-2 rounded">
            <span>Submit</span>

        </button>

        @if (session()->has('message'))
        <p class="text-green-600">{{ session('message') }}</p>
        @endif

        <div>
            <p>Length of name: <span x-text="$wire.name ? $wire.name.length : 0"></span></p>
            <p>Email value: {{ $this->email }}</p>
        </div>

        @error('name')
        <span class="error text-red-500">{{ $message }}</span>
        @enderror
        @error('email')
        <span class="error text-red-500">{{ $message }}</span>
        @enderror
    </form>

    <button wire:click="loadUsers" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded">
        <span wire:loading.remove wire:target="loadUsers">Get Data</span>
        <span wire:loading.delay.long wire:target="loadUsers">Loading...</span>
    </button>

    <button wire:click="resetUsers" class="bg-blue-500 text-white px-4 py-2 ">Reset</button>
    <span wire:loading.delay.short wire:target="resetUsers">Resetting</span>

    <ul class="mt-4">
        @foreach($users as $user)
        <li class="py-1">{{ $user->name ?? 'No name' }}</li>
        <li class="py-1">{{ $user->email ?? 'No Email' }}</li>
        <li class="py-1">{{ $user->created_at->diffForHumans() }}</li>
        @endforeach
    </ul>
</div>