<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ env('APP_NAME', 'Isipa Resultat Checker') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">

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

        <div
            class="w-full sm:max-w-lg mt-6 px-6 py-10 rounded-md bg-white dark:bg-gray-800 shadow-lg overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
    <div id="pwa-install-banner" class="bg-sky-600"
        style="display:none;position:fixed;top:0;left:0;width:100%;color:#fff;text-align:center;z-index:1000;padding:8px;">
        <span>Tu veux installer l'application ?</span>
        <button id="pwa-custom-install-btn"
            style="margin-left:8px;background:white;color:#2196f3;padding:4px 12px;border:none;border-radius:4px;">Installer</button>
        <button onclick="document.getElementById('pwa-install-banner').style.display='none'"
            style="margin-left:8px;background:none;color:white;border:none;">×</button>
    </div>

    <script>
        let deferredPrompt = null;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            document.getElementById('pwa-install-banner').style.display = 'block';
        });

        document.getElementById('pwa-custom-install-btn').addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt = null;
                document.getElementById('pwa-install-banner').style.display = 'none';
            }
        });
    </script>

    @RegisterServiceWorkerScript
</body>

</html>
