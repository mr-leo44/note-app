@php
    $type = $type ?? 'info';
    $base = 'flex items-center p-4 mb-4 rounded-lg';
    $color = [
        'success' => 'text-green-800 bg-green-100 dark:bg-green-200 dark:text-green-900',
        'error' => 'text-red-800 bg-red-100 dark:bg-red-200 dark:text-red-900',
        'warning' => 'text-yellow-800 bg-yellow-100 dark:bg-yellow-200 dark:text-yellow-900',
        'info' => 'text-blue-800 bg-blue-100 dark:bg-blue-200 dark:text-blue-900',
    ][$type] ?? $color['info'];
    $classes = "$base $color";
@endphp
<div class="{{ $classes }}" x-data="{ show: true }" x-show="show" role="alert">
    <div class="flex-1">
        <span class="ms-2 text-sm font-medium">
            {{ $slot }}
        </span>
    </div>
    <button type="button" @click="show = false" class="ml-4 flex items-center justify-center w-6 h-6 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400" aria-label="Fermer">
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
</div>
