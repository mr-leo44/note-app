<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')
        @include('layouts.sidebar')

        <!-- Page Content -->

        <main class="px-4 pt-28 sm:ml-64">
            <!-- Page Heading -->
            @isset($header)
                <header
                    class="fixed flex left-0 md:left-64 mx-4 md:ml-4 top-20 right-0 bg-white dark:bg-gray-800 dark:text-white shadow rounded-lg">
                    <div class="max-w-8xl w-full px-3 md:p-4">
                        {{ $header }}
                    </div>
                </header>
            @endisset
            {{ $slot }}
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    @stack('scripts')
</body>

</html>
