<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center font-medium p-2 md:px-4 md:py-2 text-white bg-sky-600 hover:bg-sky-700 focus:ring-4 focus:outline-none focus:ring-sky-300 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest focus:bg-sky-700 dark:focus:bg-white active:bg-sky-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 text-center']) }}>
    {{ $slot }}
</button>
