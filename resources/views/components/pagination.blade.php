@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6">
        <p class="text-sm text-gray-500 order-2 sm:order-1">
            Menampilkan {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
        </p>

        <div class="flex items-center gap-1 order-1 sm:order-2">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-300 bg-gray-50 border border-gray-200 rounded-lg cursor-not-allowed">&laquo;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">&laquo;</a>
            @endif

            {{-- Pages --}}
            @foreach ($paginator->links()->elements[0] ?? [] as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="px-3 py-2 text-sm font-semibold text-white bg-[#1e3a5f] border border-[#1e3a5f] rounded-lg shadow-sm">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">&raquo;</a>
            @else
                <span class="px-3 py-2 text-sm text-gray-300 bg-gray-50 border border-gray-200 rounded-lg cursor-not-allowed">&raquo;</span>
            @endif
        </div>
    </nav>
@endif
