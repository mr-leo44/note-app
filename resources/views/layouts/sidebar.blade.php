<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 md:w-64 w-60 h-screen pt-28 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    aria-label="Sidebar">
    <div class="h-full px-2 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            @php
                $isNotJury = auth()->user()->account->accountable_type !== 'App\Models\Jury';
            @endphp

            @if ($isNotJury)
                <li>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{-- Dashboard icon --}}
                        <svg viewBox="0 -0.5 25 25"
                            class="w-8 h-8 text-gray-800 transition duration-75 dark:text-gray-500 group-hover:text-gray-900 dark:group-hover:text-white -ms-1"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M9.918 10.0005H7.082C6.66587 9.99708 6.26541 10.1591 5.96873 10.4509C5.67204 10.7427 5.50343 11.1404 5.5 11.5565V17.4455C5.5077 18.3117 6.21584 19.0078 7.082 19.0005H9.918C10.3341 19.004 10.7346 18.842 11.0313 18.5502C11.328 18.2584 11.4966 17.8607 11.5 17.4445V11.5565C11.4966 11.1404 11.328 10.7427 11.0313 10.4509C10.7346 10.1591 10.3341 9.99708 9.918 10.0005Z"
                                    stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M9.918 4.0006H7.082C6.23326 3.97706 5.52559 4.64492 5.5 5.4936V6.5076C5.52559 7.35629 6.23326 8.02415 7.082 8.0006H9.918C10.7667 8.02415 11.4744 7.35629 11.5 6.5076V5.4936C11.4744 4.64492 10.7667 3.97706 9.918 4.0006Z"
                                    stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M15.082 13.0007H17.917C18.3333 13.0044 18.734 12.8425 19.0309 12.5507C19.3278 12.2588 19.4966 11.861 19.5 11.4447V5.55666C19.4966 5.14054 19.328 4.74282 19.0313 4.45101C18.7346 4.1592 18.3341 3.9972 17.918 4.00066H15.082C14.6659 3.9972 14.2654 4.1592 13.9687 4.45101C13.672 4.74282 13.5034 5.14054 13.5 5.55666V11.4447C13.5034 11.8608 13.672 12.2585 13.9687 12.5503C14.2654 12.8421 14.6659 13.0041 15.082 13.0007Z"
                                    stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M15.082 19.0006H17.917C18.7661 19.0247 19.4744 18.3567 19.5 17.5076V16.4936C19.4744 15.6449 18.7667 14.9771 17.918 15.0006H15.082C14.2333 14.9771 13.5256 15.6449 13.5 16.4936V17.5066C13.525 18.3557 14.2329 19.0241 15.082 19.0006Z"
                                    stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                    stroke-linejoin="round">
                                </path>
                            </g>
                        </svg>
                        <span class="ms-1">{{ __('Dashboard') }}</span>
                    </x-nav-link>
                </li>
                <li>
                    <button type="button"
                        class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                        aria-controls="dropdown-academics" data-collapse-toggle="dropdown-academics">
                        <div class="flex gap-2 items-center">
                            {{-- Graduation-cap icon --}}
                            <svg version="1.1"
                                class="w-6 h-6 text-gray-800 transition duration-75 dark:text-gray-500 group-hover:text-gray-900 dark:group-hover:text-white"
                                id="Layer_1" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"
                                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 37 32"
                                enable-background="new 0 0 37 32" xml:space="preserve" fill="currentColor" >
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <g>
                                        <path fill="currentColor"
                                            d="M36.078,7.173L19.876,0.346c-0.865-0.365-1.858-0.365-2.728,0L0.922,7.183C0.362,7.42,0.009,7.944,0,8.554 c-0.009,0.607,0.329,1.143,0.881,1.394L2.59,10.73C2.538,10.809,2.5,10.898,2.5,11v9c0,0.005,0.003,0.01,0.003,0.016 c-1.172,0.207-2.066,1.226-2.066,2.456c0,1.111,0.733,2.043,1.736,2.368l-1.798,4.164c-0.271,0.629-0.206,1.326,0.18,1.912 C1.002,31.595,1.787,32,2.656,32l0.289-0.014c0.813-0.066,1.539-0.481,1.941-1.111c0.344-0.537,0.418-1.182,0.203-1.767 L3.541,24.89c1.086-0.272,1.896-1.248,1.896-2.418c0-1.188-0.833-2.18-1.945-2.433C3.493,20.025,3.5,20.014,3.5,20v-8.853l4,1.833 V20h0.01c0.103,2.257,5.827,3.439,11.49,3.439S30.387,22.257,30.49,20h0.01v-7.551l5.607-2.507C36.664,9.694,37.006,9.16,37,8.549 C36.993,7.938,36.641,7.41,36.078,7.173z M4.045,30.336c-0.235,0.368-0.677,0.613-1.186,0.654L2.656,31 c-0.531,0-1.005-0.236-1.266-0.634c-0.2-0.304-0.234-0.647-0.097-0.966l1.533-3.552l1.323,3.604 C4.259,29.746,4.221,30.061,4.045,30.336z M4.438,22.472c0,0.827-0.673,1.5-1.5,1.5s-1.5-0.673-1.5-1.5s0.673-1.5,1.5-1.5 S4.438,21.645,4.438,22.472z M29.5,18.419c-1.898-1.305-6.219-1.98-10.5-1.98s-8.602,0.675-10.5,1.98v-7.278 c0-1.097,3.185-2.641,10.266-2.641c7.004,0,10.734,1.57,10.734,2.703V18.419z M19,22.439c-6.409,0-10.5-1.48-10.5-2.5 s4.091-2.5,10.5-2.5s10.5,1.48,10.5,2.5S25.409,22.439,19,22.439z M35.699,9.03L30.5,11.354v-0.151 c0-2.181-4.825-3.703-11.734-3.703C11.922,7.5,7.5,8.929,7.5,11.141v0.741L1.297,9.038C1.017,8.911,0.999,8.646,1,8.567 s0.027-0.343,0.311-0.463l16.227-6.837c0.619-0.263,1.331-0.261,1.95,0l16.202,6.827c0.285,0.12,0.31,0.386,0.311,0.464 C36.001,8.638,35.981,8.903,35.699,9.03z">
                                        </path>
                                    </g>
                                </g>
                            </svg>
                            <span class="flex-1 text-left whitespace-nowrap" sidebar-toggle-item="">Programme
                                d'études</span>
                        </div>
                        <svg sidebar-toggle-item="" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <ul id="dropdown-academics" class="hidden py-2 space-y-2">
                        <li>
                            <x-nav-link :href="route('sections.index')" :active="request()->routeIs('sections.*')">
                                <span class="ms-3">Sections</span>
                            </x-nav-link>
                        </li>
                        <li>
                            <x-nav-link :href="route('departments.index')" :active="request()->routeIs('departments.*')">
                                <span class="ms-3">Départements</span>
                            </x-nav-link>
                        </li>
                    </ul>
                </li>
                <li>
                    <button type="button"
                        class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                        aria-controls="dropdown-cycles" data-collapse-toggle="dropdown-cycles">
                        <div class="flex gap-2 items-center">
                            {{-- Calendar icon for cycles --}}
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                class="w-6 h-6 text-gray-800 transition duration-75 dark:text-gray-500 group-hover:text-gray-900 dark:group-hover:text-white"
                                stroke="currentColor">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path
                                        d="M3 9H21M7 3V5M17 3V5M6 12H8M11 12H13M16 12H18M6 15H8M11 15H13M16 15H18M6 18H8M11 18H13M16 18H18M6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4802 21 18.9201 21 17.8V8.2C21 7.07989 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V17.8C3 18.9201 3 19.4802 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21Z"
                                        fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round">
                                    </path>
                                </g>
                            </svg>
                            <span class="flex-1 text-left whitespace-nowrap" sidebar-toggle-item="">Cycle
                                académique</span>
                            <svg sidebar-toggle-item="" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                    </button>
                    <ul id="dropdown-cycles" class="hidden py-2 space-y-2">
                        <li>
                            <x-nav-link :href="route('periods.index')" :active="request()->routeIs('periods.*')">
                                <span class="ms-3">Années Academique</span>
                            </x-nav-link>
                        </li>
                        <li>
                            <x-nav-link :href="route('semesters.index')" :active="request()->routeIs('semesters.*')">
                                <span class="ms-3">Semestres</span>
                            </x-nav-link>
                        </li>
                    </ul>
                </li>
                <li>
                    <x-nav-link :href="route('juries.index')" :active="request()->routeIs('juries.*')">
                        {{-- User group / jury icon --}}
                        <svg class="w-6 h-6 text-gray-700 transition duration-75 dark:text-gray-500 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 256 256">
                            <path
                                d="M117.25,157.92a60,60,0,1,0-66.5,0A95.83,95.83,0,0,0,3.53,195.63a8,8,0,1,0,13.4,8.74,80,80,0,0,1,134.14,0,8,8,0,0,0,13.4-8.74A95.83,95.83,0,0,0,117.25,157.92ZM40,108a44,44,0,1,1,44,44A44.05,44.05,0,0,1,40,108Zm210.14,98.7a8,8,0,0,1-11.07-2.33A79.83,79.83,0,0,0,172,168a8,8,0,0,1,0-16,44,44,0,1,0-16.34-84.87,8,8,0,1,1-5.94-14.85,60,60,0,0,1,55.53,105.64,95.83,95.83,0,0,1,47.22,37.71A8,8,0,0,1,250.14,206.7Z">
                            </path>
                        </svg>
                        <span class="ms-3">{{ __('Jurys') }}</span>
                    </x-nav-link>
                </li>
                <li>
                    <button type="button"
                        class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                        aria-controls="dropdown-courses" data-collapse-toggle="dropdown-courses">
                        <div class="flex gap-2 items-center pl-0">
                            {{-- Book icon for courses --}}
                            <svg fill="currentColor" stroke="currentColor"
                                class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-500 group-hover:text-gray-900 dark:group-hover:text-white"
                                viewBox="0 0 32 32" id="icon" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <defs>
                                        <style>
                                            .cls-1 {
                                                fill: none;
                                            }
                                        </style>
                                    </defs>
                                    <rect x="19" y="10" width="7" height="2"></rect>
                                    <rect x="19" y="15" width="7" height="2"></rect>
                                    <rect x="19" y="20" width="7" height="2"></rect>
                                    <rect x="6" y="10" width="7" height="2"></rect>
                                    <rect x="6" y="15" width="7" height="2"></rect>
                                    <rect x="6" y="20" width="7" height="2"></rect>
                                    <path
                                        d="M28,5H4A2.002,2.002,0,0,0,2,7V25a2.002,2.002,0,0,0,2,2H28a2.002,2.002,0,0,0,2-2V7A2.002,2.002,0,0,0,28,5ZM4,7H15V25H4ZM17,25V7H28V25Z">
                                    </path>
                                    <rect id="_Transparent_Rectangle_" data-name="&lt;Transparent Rectangle&gt;"
                                        class="cls-1"></rect>
                                </g>
                            </svg>
                            <span class="flex-1 text-left whitespace-nowrap" sidebar-toggle-item="">Cours</span>
                        </div>
                        <svg sidebar-toggle-item="" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <ul id="dropdown-courses" class="hidden py-2 space-y-2">
                        <li>
                            <x-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.*')">
                                <span class="ms-3">Liste</span>
                            </x-nav-link>
                        </li>
                        <li>
                            <x-nav-link :href="route('course-categories.index')" :active="request()->routeIs('course-categories.*')">
                                <span class="ms-3">Catégories</span>
                            </x-nav-link>
                        </li>
                    </ul>
                </li>
            @endif

            <li>
                <x-nav-link :href="route('promotions.index')" :active="request()->routeIs('promotions.*')">
                    {{-- Tag icon for promotions --}}
                    <svg class="w-6 h-6 text-gray-700 transition duration-75 dark:text-gray-500 group-hover:text-gray-900 dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 256 256">
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
                    <svg viewBox="0 0 24 24"
                        class="w-6 h-6 text-gray-700 transition duration-75 dark:text-gray-500 group-hover:text-gray-900 dark:group-hover:text-white"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M13 3H8.2C7.0799 3 6.51984 3 6.09202 3.21799C5.71569 3.40973 5.40973 3.71569 5.21799 4.09202C5 4.51984 5 5.0799 5 6.2V17.8C5 18.9201 5 19.4802 5.21799 19.908C5.40973 20.2843 5.71569 20.5903 6.09202 20.782C6.51984 21 7.0799 21 8.2 21H12M13 3L19 9M13 3V7.4C13 7.96005 13 8.24008 13.109 8.45399C13.2049 8.64215 13.3578 8.79513 13.546 8.89101C13.7599 9 14.0399 9 14.6 9H19M19 9V11M9 17H11M9 13H13M9 9H10M19.2686 19.2686L21 21M20 17.5C20 18.8807 18.8807 20 17.5 20C16.1193 20 15 18.8807 15 17.5C15 16.1193 16.1193 15 17.5 15C18.8807 15 20 16.1193 20 17.5Z"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                        </g>
                    </svg>

                    <span class="ms-3">{{ __('Publications') }}</span>
                </x-nav-link>
            </li>
        </ul>
    </div>
</aside>
