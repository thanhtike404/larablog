<x-layout>
    <ul>
        @foreach ($users as $user)
            <li>name: {{ $user->name }} - email:{{ $user->email }}</li>
            <br>
        @endforeach
    </ul>
</x-layout>