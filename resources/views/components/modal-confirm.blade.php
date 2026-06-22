@props([
    'id' => 'modal-confirm',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin ingin melanjutkan?',
    'confirmText' => 'Ya, Hapus',
    'cancelText' => 'Batal',
    'confirmClass' => 'bg-red-600 hover:bg-red-700',
    'formAction' => null,
    'formMethod' => 'POST',
])

<div
    x-data="{ show: false }"
    x-cloak
    x-init="window.showModal{{ ucfirst($id) }} = () => { show = true }"
    x-show="show"
    @@keydown.escape.window="show = false"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4"
>
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @@click="show = false"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm"
    ></div>

    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative bg-white rounded-xl shadow-xl border border-gray-200 w-full max-w-md p-6 z-10"
    >
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ $message }}</p>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button @@click="show = false" type="button" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                {{ $cancelText }}
            </button>

            @if ($formAction)
                <form action="{{ $formAction }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white rounded-lg transition-colors {{ $confirmClass }}">
                        {{ $confirmText }}
                    </button>
                </form>
            @else
                <button type="button" class="px-4 py-2.5 text-sm font-medium text-white rounded-lg transition-colors {{ $confirmClass }}">
                    {{ $confirmText }}
                </button>
            @endif
        </div>
    </div>
</div>
