@if ($paginator->hasPages())
    <nav class="flex items-center justify-center gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span
                class="px-4 py-2.5 rounded-lg bg-gray-200 text-gray-500 font-['Cormorant_Garamond'] text-sm cursor-not-allowed">
                ← Sebelumnya
            </span>
        @else
            <button wire:click="previousPage"
                class="px-4 py-2.5 rounded-lg bg-[#7A8C5C] text-white font-['Cormorant_Garamond'] text-sm font-semibold transition-all duration-300 hover:bg-[#6B7A4D] hover:shadow-md">
                ← Sebelumnya
            </button>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-3 py-2.5 text-gray-500 font-['Cormorant_Garamond']">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span
                            class="px-4 py-2.5 rounded-lg bg-[#6B3A2A] text-white font-['Cormorant_Garamond'] text-sm font-bold shadow-md">
                            {{ $page }}
                        </span>
                    @else
                        <button wire:click="gotoPage({{ $page }})"
                            class="px-4 py-2.5 rounded-lg bg-[#FFF9F2] text-[#7A8C5C] font-['Cormorant_Garamond'] text-sm font-semibold border-2 border-[#7A8C5C] transition-all duration-300 hover:bg-[#7A8C5C] hover:text-white">
                            {{ $page }}
                        </button>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage"
                class="px-4 py-2.5 rounded-lg bg-[#7A8C5C] text-white font-['Cormorant_Garamond'] text-sm font-semibold transition-all duration-300 hover:bg-[#6B7A4D] hover:shadow-md">
                Selanjutnya →
            </button>
        @else
            <span
                class="px-4 py-2.5 rounded-lg bg-gray-200 text-gray-500 font-['Cormorant_Garamond'] text-sm cursor-not-allowed">
                Selanjutnya →
            </span>
        @endif
    </nav>
@endif
