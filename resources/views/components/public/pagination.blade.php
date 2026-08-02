@props(['paginator'])

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="mt-10">
        <ul class="flex flex-wrap items-center justify-center gap-2">
            @if ($paginator->onFirstPage())
                <li><span class="px-3 py-2 text-sm text-charcoal/40">Previous</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-sm font-medium text-forest hover:text-teal rounded-md">Previous</a></li>
            @endif

            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li><span class="px-3 py-2 text-sm font-semibold bg-forest text-ivory rounded-md" aria-current="page">{{ $page }}</span></li>
                @else
                    <li><a href="{{ $url }}" class="px-3 py-2 text-sm text-charcoal hover:text-forest rounded-md">{{ $page }}</a></li>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-sm font-medium text-forest hover:text-teal rounded-md">Next</a></li>
            @else
                <li><span class="px-3 py-2 text-sm text-charcoal/40">Next</span></li>
            @endif
        </ul>
    </nav>
@endif
