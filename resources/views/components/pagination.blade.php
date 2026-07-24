@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex items-center justify-between pt-2">
        <!-- Tampilan Mobile (< 640px) -->
        <div class="flex justify-between flex-1 sm:hidden gap-2">
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 text-xs font-semibold text-gray-400 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed">
                    &laquo; Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 text-xs font-semibold text-[#00236F] bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-2xs">
                    &laquo; Sebelumnya
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 text-xs font-semibold text-[#00236F] bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-2xs">
                    Berikutnya &raquo;
                </a>
            @else
                <span class="px-4 py-2 text-xs font-semibold text-gray-400 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed">
                    Berikutnya &raquo;
                </span>
            @endif
        </div>

        <!-- Tampilan Desktop (>= 640px) -->
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <!-- Informasi Jumlah Data -->
            <div>
                <p class="text-xs text-gray-500 font-medium">
                    Menampilkan
                    <span class="font-bold text-[#1f2937]">{{ $paginator->firstItem() ?? 0 }}</span>
                    sampai
                    <span class="font-bold text-[#1f2937]">{{ $paginator->lastItem() ?? 0 }}</span>
                    dari
                    <span class="font-bold text-[#1f2937]">{{ $paginator->total() }}</span>
                    data
                </p>
            </div>

            <!-- Angka & Tombol Halaman -->
            <div>
                <span class="relative z-0 inline-flex items-center gap-1.5">
                    {{-- Tombol Previous --}}
                    @if ($paginator->onFirstPage())
                        <span class="px-3 py-2 text-xs font-semibold text-gray-300 bg-gray-50 border border-gray-300 rounded-lg cursor-not-allowed">
                            &lsaquo;
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-xs font-semibold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-[#00236F] transition">
                            &lsaquo;
                        </a>
                    @endif

                    {{-- Element Angka Halaman --}}
                    @foreach ($elements as $element)
                        {{-- Separator Titik-Titik (...) --}}
                        @if (is_string($element))
                            <span class="px-3 py-2 text-xs font-semibold text-gray-400 bg-white border border-gray-300 rounded-lg">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Array Link Angka --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span class="px-3.5 py-2 text-xs font-bold text-white bg-[#00236F] border border-[#00236F] rounded-lg shadow-2xs">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="px-3 py-2 text-xs font-semibold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-[#00236F] transition">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Tombol Next --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-xs font-semibold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-[#00236F] transition">
                            &rsaquo;
                        </a>
                    @else
                        <span class="px-3 py-2 text-xs font-semibold text-gray-300 bg-gray-50 border border-gray-300 rounded-lg cursor-not-allowed">
                            &rsaquo;
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif