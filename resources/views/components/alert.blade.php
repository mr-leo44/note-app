@php
    $type = $type ?? 'info';
    $base = 'flex items-center p-4 mb-4 rounded-lg shadow-lg gap-2';
    $color = [
        'success' => 'text-green-800 bg-green-100 border border-green-300',
        'error' => 'text-red-800 bg-red-100 border border-red-300',
        'warning' => 'text-yellow-800 bg-yellow-100 border border-yellow-300',
        'info' => 'text-blue-800 bg-blue-100 border border-blue-300',
    ][$type] ?? 'text-blue-800 bg-blue-100 border border-blue-300';
    $icon = [
        'success' => '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>',
        'error' => '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>',
        'warning' => '<svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 17a5 5 0 100-10 5 5 0 000 10z" /></svg>',
        'info' => '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" /></svg>',
    ][$type] ?? '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" /></svg>';
    $classes = "$base $color";
@endphp
<div class="{{ $classes }} fixed top-6 left-1/2 transform -translate-x-1/2 z-50" x-data="{ show: true }" x-show="show" role="alert" id="alert">
    {!! $icon !!}
    <span class="text-sm font-medium flex-1">
        {{ $slot }}
    </span>
    <button type="button" @click="show = false" class="ml-4 text-inherit hover:text-black focus:outline-none" aria-label="Fermer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
    </button>
</div>

@push('scripts')
    <script>
        setTimeout(() => {
            document.querySelector('div#alert').remove();
        }, 5000);
    </script>
@endpush
