@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex flex-wrap items-center gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span
                class="inline-flex items-center gap-2 rounded-full border border-[#E6D7C4] bg-[#F8F3EA] px-4 py-2 text-sm font-semibold text-[#B8A38B] cursor-not-allowed">
                <span aria-hidden="true">&larr;</span>
                <span>Sebelumnya</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
                class="inline-flex items-center gap-2 rounded-full border border-[#7A8C5C] bg-white px-4 py-2 text-sm font-semibold text-[#6A7941] transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#F0F7E6] hover:shadow-sm">
                <span aria-hidden="true">&larr;</span>
                <span>Sebelumnya</span>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-2 text-sm font-semibold text-[#B79F87]">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span
                            class="inline-flex min-w-10 items-center justify-center rounded-full bg-[#7A8C5C] px-4 py-2 text-sm font-bold text-white shadow-sm ring-2 ring-[#DCE7C8]">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                            class="inline-flex min-w-10 items-center justify-center rounded-full border border-[#D9D0C2] bg-white px-4 py-2 text-sm font-semibold text-[#6B4C3B] transition-all duration-200 hover:-translate-y-0.5 hover:border-[#7A8C5C] hover:bg-[#FFF9F2] hover:text-[#5C6B44]">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
                class="inline-flex items-center gap-2 rounded-full border border-[#7A8C5C] bg-white px-4 py-2 text-sm font-semibold text-[#6A7941] transition-all duration-200 hover:-translate-y-0.5 hover:bg-[#F0F7E6] hover:shadow-sm">
                <span>Selanjutnya</span>
                <span aria-hidden="true">&rarr;</span>
            </a>
        @else
            <span
                class="inline-flex items-center gap-2 rounded-full border border-[#E6D7C4] bg-[#F8F3EA] px-4 py-2 text-sm font-semibold text-[#B8A38B] cursor-not-allowed">
                <span>Selanjutnya</span>
                <span aria-hidden="true">&rarr;</span>
            </span>
        @endif
    </nav>
@endif
