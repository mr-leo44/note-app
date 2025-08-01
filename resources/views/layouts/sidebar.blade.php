<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 md:w-64 w-52 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            @php
                $isNotJury = auth()->user()->account->accountable_type !== 'App\Models\Jury';
            @endphp

            @if ($isNotJury)
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
                <li>
                    <x-nav-link :href="route('sections.index')" :active="request()->routeIs('sections.*')">
                        {{-- Faculty icon --}}
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path
                                d="M12 14l6.16-3.422A12.083 12.083 0 0118 19.128M12 14l-6.16-3.422A12.083 12.083 0 006 19.128" />
                        </svg>
                        <span class="ms-3">{{ __('Sections') }}</span>
                    </x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="route('juries.index')" :active="request()->routeIs('juries.*')">
                        {{-- User group / jury icon --}}
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24px"
                            height="24px" fill="currentColor" viewBox="0 0 256 256">
                            <path
                                d="M117.25,157.92a60,60,0,1,0-66.5,0A95.83,95.83,0,0,0,3.53,195.63a8,8,0,1,0,13.4,8.74,80,80,0,0,1,134.14,0,8,8,0,0,0,13.4-8.74A95.83,95.83,0,0,0,117.25,157.92ZM40,108a44,44,0,1,1,44,44A44.05,44.05,0,0,1,40,108Zm210.14,98.7a8,8,0,0,1-11.07-2.33A79.83,79.83,0,0,0,172,168a8,8,0,0,1,0-16,44,44,0,1,0-16.34-84.87,8,8,0,1,1-5.94-14.85,60,60,0,0,1,55.53,105.64,95.83,95.83,0,0,1,47.22,37.71A8,8,0,0,1,250.14,206.7Z">
                            </path>
                        </svg>
                        <span class="ms-3">{{ __('Jurys') }}</span>
                    </x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.*')">
                        {{-- Book icon for courses --}}
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 256 256">
                            <path
                                d="M224,48H160a40,40,0,0,0-32,16A40,40,0,0,0,96,48H32A16,16,0,0,0,16,64V192a16,16,0,0,0,16,16H96a24,24,0,0,1,24,24,8,8,0,0,0,16,0,24,24,0,0,1,24-24h64a16,16,0,0,0,16-16V64A16,16,0,0,0,224,48ZM96,192H32V64H96a24,24,0,0,1,24,24V200A39.81,39.81,0,0,0,96,192Zm128,0H160a39.81,39.81,0,0,0-24,8V88a24,24,0,0,1,24-24h64Z">
                            </path>
                        </svg>
                        <span class="ms-3">{{ __('Cours') }}</span>
                    </x-nav-link>
                </li>
                <li>
                    <x-nav-link :href="route('periods.index')" :active="request()->routeIs('periods.*')">
                        {{-- Calendar icon for périodes --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" aria-hidden="true" fill="currentColor"
                            viewBox="0 0 256 256">
                            <path
                                d="M208,32H184V24a8,8,0,0,0-16,0v8H88V24a8,8,0,0,0-16,0v8H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32ZM72,48v8a8,8,0,0,0,16,0V48h80v8a8,8,0,0,0,16,0V48h24V80H48V48ZM208,208H48V96H208V208Zm-96-88v64a8,8,0,0,1-16,0V132.94l-4.42,2.22a8,8,0,0,1-7.16-14.32l16-8A8,8,0,0,1,112,120Zm59.16,30.45L152,176h16a8,8,0,0,1,0,16H136a8,8,0,0,1-6.4-12.8l28.78-38.37A8,8,0,1,0,145.07,132a8,8,0,1,1-13.85-8A24,24,0,0,1,176,136,23.76,23.76,0,0,1,171.16,150.45Z">
                            </path>
                        </svg>
                        <span class="ms-3">{{ __('Périodes') }}</span>
                    </x-nav-link>
                </li>
            @endif

            <li>
                <x-nav-link :href="route('promotions.index')" :active="request()->routeIs('promotions.*')">
                    {{-- Tag icon for promotions --}}
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24px"
                        height="24px" fill="currentColor" viewBox="0 0 256 256">
                        <path
                            d="M244.8,150.4a8,8,0,0,1-11.2-1.6A51.6,51.6,0,0,0,192,128a8,8,0,0,1-7.37-4.89,8,8,0,0,1,0-6.22A8,8,0,0,1,192,112a24,24,0,1,0-23.24-30,8,8,0,1,1-15.5-4A40,40,0,1,1,219,117.51a67.94,67.94,0,0,1,27.43,21.68A8,8,0,0,1,244.8,150.4ZM190.92,212a8,8,0,1,1-13.84,8,57,57,0,0,0-98.16,0,8,8,0,1,1-13.84-8,72.06,72.06,0,0,1,33.74-29.92,48,48,0,1,1,58.36,0A72.06,72.06,0,0,1,190.92,212ZM128,176a32,32,0,1,0-32-32A32,32,0,0,0,128,176ZM72,120a8,8,0,0,0-8-8A24,24,0,1,1,87.24,82a8,8,0,1,0,15.5-4A40,40,0,1,0,37,117.51,67.94,67.94,0,0,0,9.6,139.19a8,8,0,1,0,12.8,9.61A51.6,51.6,0,0,1,64,128,8,8,0,0,0,72,120Z">
                        </path>
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
