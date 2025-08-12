<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Laravel App' }}</title>
    @stack('meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <header>

        <x-navbar />

    </header>

    <main class="container mx-auto px-4 py-8">

        {{ $slot }}
    </main>

    <footer>
        <!-- Your footer here -->
    </footer>
</body>

</html>