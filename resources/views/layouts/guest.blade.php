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
        @PwaHead
    </head>
    <body class="font-sans text-gray-700 antialiased">
        <div class="min-h-screen flex flex-col p-4 md:p-0 justify-center items-center bg-gray-100 dark:bg-gray-800">
            <div>
                <a href="/">
                    <img src="{{ asset('img/graduated-logo.png') }}" class="w-16 md:w-20 h-16 md:h-20" alt="">
                </a>
            </div>

            <div class="w-full sm:max-w-lg mt-6 px-6 py-10 rounded-md bg-white dark:bg-gray-800 shadow-lg overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
        @RegisterServiceWorkerScript
    </body>
</html>
