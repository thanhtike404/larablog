<!DOCTYPE html>
<html lang="en"
  x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
  x-init="
        $watch('darkMode', value => {
          localStorage.setItem('darkMode', value);
          if (value) {
            document.documentElement.classList.add('dark');
          } else {
            document.documentElement.classList.remove('dark');
          }
        });
        darkMode && document.documentElement.classList.add('dark');
      "
  :class="{ 'dark': darkMode }">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'Laravel App' }}</title>
  @stack('meta')
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>

<body class="bg-white text-slate-900 transition-colors">
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <livewire:layout.navigation />

    <!-- Page Heading -->
    @if (isset($header))
    <header class="bg-white dark:bg-gray-800 shadow">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{ $header }}
      </div>
    </header>
    @endif

    <!-- Page Content -->
    <main>

      {{ $slot }}
    </main>
  </div>


  @stack('scripts')
  @livewireScripts
</body>

</html>