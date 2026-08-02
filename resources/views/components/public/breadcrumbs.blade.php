@props(['items' => []])

<nav aria-label="Breadcrumb" class="mb-6">
    <ol class="flex flex-wrap items-center gap-2 text-sm text-charcoal/70">
        <li><a href="{{ route('site.home') }}" class="hover:text-forest">Home</a></li>
        @foreach($items as $item)
            <li aria-hidden="true">/</li>
            <li>
                @if(!empty($item['url']))
                    <a href="{{ $item['url'] }}" class="hover:text-forest">{{ $item['label'] }}</a>
                @else
                    <span class="text-charcoal font-medium" aria-current="page">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
