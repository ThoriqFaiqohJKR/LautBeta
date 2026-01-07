@if ($lastPage > 1)
<nav class="flex items-center justify-center gap-1">

    @if ($page == 1)
    <span class="px-3 py-2 text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke-width="1.5" stroke="currentColor" class="size-4">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" />
        </svg>
    </span>
    @else
    <button wire:click="prevPage" class="px-3 py-2 hover:text-blue-600">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke-width="1.5" stroke="currentColor" class="size-4">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5" />
        </svg>
    </button>
    @endif

    @for ($i = max(1, $page - 2); $i <= min($lastPage, $page + 2); $i++)
        @if ($i==$page)
        <span class="px-3 py-1 border border-gray-400 rounded-full text-sm">
        {{ $i }}
        </span>
        @else
        <button wire:click="goToPage({{ $i }})"
            class="px-3 py-1 rounded-full hover:bg-gray-100 text-sm">
            {{ $i }}
        </button>
        @endif
        @endfor

        @if ($page < $lastPage)
            <button wire:click="nextPage" class="px-3 py-2 hover:text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
            </svg>
            </button>
            @else
            <span class="px-3 py-2 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
                </svg>
            </span>
            @endif

</nav>
@endif