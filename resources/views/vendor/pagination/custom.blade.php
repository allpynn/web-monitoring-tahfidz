<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
    {{-- Mobile View --}}
    <div class="flex justify-between flex-1 sm:hidden">
        @if ($paginator->onFirstPage())
            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md dark:text-gray-400 dark:bg-gray-800 dark:border-gray-600">
                Sebelumnya
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:text-gray-400 dark:bg-gray-800 dark:border-gray-600 dark:hover:text-gray-300 dark:active:bg-gray-700 dark:focus:border-blue-700">
                Sebelumnya
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:text-gray-400 dark:bg-gray-800 dark:border-gray-600 dark:hover:text-gray-300 dark:active:bg-gray-700 dark:focus:border-blue-700">
                Selanjutnya
            </a>
        @else
            <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md dark:text-gray-400 dark:bg-gray-800 dark:border-gray-600">
                Selanjutnya
            </span>
        @endif
    </div>

    {{-- Desktop View --}}
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-700 leading-5 dark:text-gray-400">
                Menampilkan
                @if ($paginator->firstItem())
                    <span class="font-bold">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="font-bold">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                dari
                <span class="font-bold">{{ $paginator->total() }}</span>
                hasil
            </p>
        </div>

        <div class="flex items-center gap-4">
            {{-- Per Page Selector --}}
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-gray-400 uppercase">Baris:</span>
                <select onchange="const url = new URL(window.location.href); url.searchParams.set('per_page', this.value); url.searchParams.set('page', 1); window.location.href = url.toString();" 
                        class="text-xs border-gray-200 dark:border-gray-700 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-white dark:bg-gray-900 dark:text-gray-300 py-1 pl-2 pr-8 font-bold cursor-pointer">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 || !request('per_page') ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>

            <span class="relative z-0 inline-flex shadow-sm rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 bg-white dark:bg-gray-800 cursor-default leading-5 border-r border-gray-200 dark:border-gray-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 leading-5 hover:bg-gray-50 dark:hover:bg-gray-700 transition" aria-label="Sebelumnya">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span aria-disabled="true" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 cursor-default leading-5">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-900/30 border-r border-gray-200 dark:border-gray-700 cursor-default leading-5">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 leading-5 hover:bg-gray-50 dark:hover:bg-gray-700 transition" aria-label="Halaman {{ $page }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white dark:bg-gray-800 leading-5 hover:bg-gray-50 dark:hover:bg-gray-700 transition" aria-label="Selanjutnya">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <span aria-disabled="true" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 bg-white dark:bg-gray-800 cursor-default leading-5">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </span>
        </div>
    </div>
</nav>
