@props(['type' => 'success', 'message' => ''])

@php
    $config = [
        'success' => ['bg-emerald-50 border-emerald-200 text-emerald-800', 'text-emerald-600', '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />'],
        'error'   => ['bg-red-50 border-red-200 text-red-800', 'text-red-600', '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />'],
        'warning' => ['bg-amber-50 border-amber-200 text-amber-800', 'text-amber-600', '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />'],
        'info'    => ['bg-blue-50 border-blue-200 text-blue-800', 'text-blue-600', '<path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />'],
    ];
    [$bg, $iconColor, $icon] = $config[$type];
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="flex items-start gap-3 px-4 py-3.5 mb-5 border rounded-xl {{ $bg }}"
    role="alert"
>
    <svg class="w-5 h-5 shrink-0 mt-0.5 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        {!! $icon !!}
    </svg>
    <p class="text-sm font-medium flex-1">{{ $message }}</p>
    <button @@click="show = false" type="button" class="p-1 rounded-md hover:opacity-70 transition-opacity shrink-0">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
        </svg>
    </button>
</div>
