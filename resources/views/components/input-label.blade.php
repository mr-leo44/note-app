@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm md:text-md text-gray-700 dark:text-gray-300']) }}>
    {{ $value ?? $slot }}
</label>
