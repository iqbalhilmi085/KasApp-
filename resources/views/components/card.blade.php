@props(['title' => null, 'icon' => null, 'padding' => true, 'class' => ''])

<div class="bg-white rounded-xl border border-gray-200 shadow-sm {{ $class }}">
    @if ($title)
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
            @if ($icon)
                <svg class="w-5 h-5 text-gray-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    {!! $icon !!}
                </svg>
            @endif
            <h3 class="text-base font-semibold text-gray-900">{{ $title }}</h3>
        </div>
    @endif

    <div class="{{ $padding ? 'p-6' : '' }}">
        {{ $slot }}
    </div>
</div>
