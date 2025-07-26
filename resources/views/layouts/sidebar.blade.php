<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            <li>
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                        <path
                            d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                            d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                    </svg>
                    <span class="ms-3">{{ __('Dashboard') }}</span>
                </x-nav-link>
            </li>
            @php
                $isNotJury = auth()->user()->account->accountable_type !== 'App\Models\Jury';
            @endphp

            @if ($isNotJury)
                <li>
                    <x-nav-link :href="route('faculties.index')" :active="request()->routeIs('faculties.*')">
                        {{-- Faculty icon --}}
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path
                                d="M12 14l6.16-3.422A12.083 12.083 0 0118 19.128M12 14l-6.16-3.422A12.083 12.083 0 006 19.128" />
                        </svg>
                        <span class="ms-3">{{ __('Facultés') }}</span>
                    </x-nav-link>
                </li>
                
                <li>
                    <x-nav-link :href="route('juries.index')" :active="request()->routeIs('juries.*')">
                        {{-- User group / jury icon --}}
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path
                                d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            <path d="M16 7a4 4 0 11-8 0" />
                        </svg>
                        <span class="ms-3">{{ __('Jurys') }}</span>
                    </x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.*')">
                        {{-- Book icon for courses --}}
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path
                                d="M12 20h9M12 4h9M4 6h.01M4 10h.01M4 14h.01M4 18h.01M4 6a2 2 0 012-2h4a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" />
                        </svg>
                        <span class="ms-3">{{ __('Cours') }}</span>
                    </x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="route('periods.index')" :active="request()->routeIs('periods.*')">
                        {{-- Calendar icon for périodes --}}
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path
                                d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V11a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span class="ms-3">{{ __('Périodes') }}</span>
                    </x-nav-link>
                </li>
            @endif

            <li>
                <x-nav-link :href="route('promotions.index')" :active="request()->routeIs('promotions.*')">
                    {{-- Tag icon for promotions --}}
                    <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M7 7h.01M7 7a5 5 0 017 0l7 7a5 5 0 010 7l-7 7a5 5 0 01-7 0l-7-7a5 5 0 010-7z" />
                    </svg>
                    <span class="ms-3">{{ __('Promotions') }}</span>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link :href="route('publications.index')" :active="request()->routeIs('publications.*')">
                    {{-- Document icon for publications --}}
                    <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M9 12h6m-6 4h6M8 6h8l3 3v10a1 1 0 01-1 1H6a1 1 0 01-1-1V6a1 1 0 011-1z" />
                    </svg>
                    <span class="ms-3">{{ __('Publications') }}</span>
                </x-nav-link>
            </li>

        </ul>
    </div>
</aside>
