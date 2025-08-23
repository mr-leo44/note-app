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
    <style>
        #install-prompt {
            display: none !important;
        }
    </style>
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

    <!-- PWA install banner (hidden by default, slide from top) -->

    <div id="pwa-install-banner" class="fixed inset-x-4 top-6 z-[1000] max-w-3xl mx-auto -translate-y-6 opacity-0 pointer-events-none transition-all duration-300"
        role="dialog" aria-live="polite" aria-hidden="true">
        <div
            class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-sky-600 text-white rounded-lg shadow-lg px-4 py-2 md:py-3 md:gap-3">
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <img src="{{ asset('img/graduated-logo.png') }}" alt=""
                    class="w-10 h-10 rounded-sm bg-white/10 p-1">
                <div>
                    <p class="font-semibold text-sm">{{ config('app.name', 'Isipa Resultat Checker') }}</p>
                    <p class="text-xs opacity-90">Installer l'application pour un accès plus rapide aux résultats.</p>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto mt-3 sm:mt-0">
                <button id="pwa-install-action"
                    class="inline-flex items-center gap-2 bg-white text-sky-600 hover:bg-white/90 rounded-md px-3 py-1 text-sm font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-white">
                    Installer
                </button>

                <button id="pwa-install-dismiss"
                    class="text-white/90 hover:text-white rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-white">
                    Fermer
                </button>

                <button id="pwa-install-never" class="text-white/60 hover:text-white/90 rounded-md px-2 py-1 text-xs"
                    title="Ne plus afficher">
                    Ne plus afficher
                </button>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const banner = document.getElementById('pwa-install-banner');
            const installBtn = document.getElementById('pwa-install-action');
            const dismissBtn = document.getElementById('pwa-install-dismiss');
            const neverBtn = document.getElementById('pwa-install-never');

            // ensure any element with id "install-button" is hidden (requested)
            const legacyInstallBtn = document.querySelector('.box-icon#install-button');
            console.log(legacyInstallBtn);
            
            if (legacyInstallBtn) legacyInstallBtn.style.display('none');

            const STORAGE_KEY = 'pwa-install-dismissed';
            let deferredPrompt = null;
            let shown = false;

            function showBanner() {
                if (!banner || shown) return;
                const never = localStorage.getItem(STORAGE_KEY);
                if (never === 'true') return;

                banner.classList.remove('pointer-events-none', '-translate-y-6', 'opacity-0');
                banner.classList.add('translate-y-0', 'opacity-100');
                banner.setAttribute('aria-hidden', 'false');
                shown = true;
            }

            function hideBanner(keepPointerEvents = false) {
                if (!banner) return;
                banner.classList.add('-translate-y-6', 'opacity-0');
                banner.classList.remove('translate-y-0', 'opacity-100');
                banner.setAttribute('aria-hidden', 'true');
                if (!keepPointerEvents) {
                    setTimeout(() => {
                        banner.classList.add('pointer-events-none');
                    }, 300);
                }
                shown = false;
            }

            // beforeinstallprompt fired by Chromium browsers
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                setTimeout(showBanner, 300);
            });

            // fallback: show only if deferredPrompt exists, after small delay
            window.addEventListener('load', () => {
                setTimeout(() => {
                    if (deferredPrompt) showBanner();
                }, 1200);
            });

            // Install action
            installBtn && installBtn.addEventListener('click', async () => {
                if (!deferredPrompt) return;
                try {
                    deferredPrompt.prompt();
                    const choice = await deferredPrompt.userChoice;
                    localStorage.setItem(STORAGE_KEY, 'true');
                    hideBanner();
                    deferredPrompt = null;
                } catch (err) {
                    hideBanner();
                }
            });

            // Dismiss action (temporary)
            dismissBtn && dismissBtn.addEventListener('click', () => {
                hideBanner();
            });

            // Never show again
            neverBtn && neverBtn.addEventListener('click', () => {
                localStorage.setItem(STORAGE_KEY, 'true');
                hideBanner();
            });

            // Accessibility: hide on Esc
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && shown) hideBanner();
            });

            // expose method to show banner programmatically if needed
            window.__showPwaInstallBanner = showBanner;
        })();
    </script>
    {{-- <script>
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
    </script> --}}

    <script>
        (function() {
            const banner = document.getElementById('pwa-install-banner');
            const installBtn = document.getElementById('pwa-install-action');
            const dismissBtn = document.getElementById('pwa-install-dismiss');
            const neverBtn = document.getElementById('pwa-install-never');

            const STORAGE_KEY = 'pwa-install-dismissed';
            let deferredPrompt = null;
            let shown = false;

            function showBanner() {
                if (!banner || shown) return;
                // don't show if user asked to never see
                const never = localStorage.getItem(STORAGE_KEY);
                if (never === 'true') return;

                banner.classList.remove('pointer-events-none');
                banner.classList.remove('translate-y-6', 'opacity-0');
                banner.classList.add('translate-y-0', 'opacity-100');
                banner.setAttribute('aria-hidden', 'false');
                shown = true;
            }

            function hideBanner(keepPointerEvents = false) {
                if (!banner) return;
                banner.classList.add('translate-y-6', 'opacity-0');
                banner.classList.remove('translate-y-0', 'opacity-100');
                banner.setAttribute('aria-hidden', 'true');
                if (!keepPointerEvents) {
                    setTimeout(() => {
                        banner.classList.add('pointer-events-none');
                    }, 300);
                }
                shown = false;
            }

            // beforeinstallprompt fired by Chromium browsers
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                // show the banner shortly after event to avoid jank
                setTimeout(showBanner, 300);
            });

            // fallback: some platforms (iOS) don't emit beforeinstallprompt, we show only if criteria met (optional)
            window.addEventListener('load', () => {
                // small UX: show banner after 2.5s for devices that can prompt (we require deferredPrompt)
                setTimeout(() => {
                    if (deferredPrompt) showBanner();
                }, 2500);
            });

            // Install action
            installBtn && installBtn.addEventListener('click', async () => {
                if (!deferredPrompt) return;
                try {
                    deferredPrompt.prompt();
                    const choice = await deferredPrompt.userChoice;
                    // if accepted or dismissed, don't show again for now
                    localStorage.setItem(STORAGE_KEY, 'true');
                    hideBanner();
                    deferredPrompt = null;
                } catch (err) {
                    // ensure banner hide on unexpected errors
                    hideBanner();
                }
            });

            // Dismiss action (temporary)
            dismissBtn && dismissBtn.addEventListener('click', () => {
                // hide for this session; allow reappearance later
                hideBanner();
            });

            // Never show again
            neverBtn && neverBtn.addEventListener('click', () => {
                localStorage.setItem(STORAGE_KEY, 'true');
                hideBanner();
            });

            // Accessibility: hide on Esc
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && shown) hideBanner();
            });

            // Clean up if Service Worker registration triggers prompt/other flows
            // Optionally expose method to show banner programmatically
            window.__showPwaInstallBanner = showBanner;
        })();
    </script>

    @RegisterServiceWorkerScript
</body>

</html>
