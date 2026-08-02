@props(['item'])

@php
    $mega = config('navigation_mega.'.$item->label);
    $children = $item->children;
    $childByUrl = $children->keyBy(fn ($c) => rtrim($c->url ?? '', '/'));
    $isActive = $item->isActive();

    $columns = [];
    if ($mega && ! empty($mega['columns'])) {
        foreach ($mega['columns'] as $col) {
            $links = [];
            foreach ($col['items'] as $spec) {
                $url = rtrim($spec['url'], '/');
                $child = $childByUrl->get($url)
                    ?? $children->first(fn ($c) => rtrim($c->url ?? '', '/') === $url);
                $links[] = [
                    'label' => $spec['label'] ?? ($child->label ?? basename($url)),
                    'url' => $spec['url'],
                    'desc' => $spec['desc'] ?? null,
                    'active' => $item->childIsActive($spec['url']),
                ];
            }
            if ($links) {
                $columns[] = ['title' => $col['title'], 'links' => $links];
            }
        }
    }

    if (! $columns) {
        $cols = min(3, max(1, (int) ceil($children->count() / 4)));
        $perCol = (int) ceil($children->count() / $cols);
        foreach ($children->values()->chunk($perCol) as $chunk) {
            $columns[] = [
                'title' => null,
                'links' => $chunk->map(fn ($c) => [
                    'label' => $c->label,
                    'url' => $c->url,
                    'desc' => null,
                    'active' => $item->childIsActive($c->url),
                ])->all(),
            ];
        }
    }

    $colCount = max(1, count($columns));
    $maxWidth = $mega['max_width'] ?? ($colCount >= 3 ? '880px' : ($colCount === 2 ? '640px' : '360px'));
    $alignEnd = in_array($item->label, ['Events & Learning', 'Resources', 'Get Involved'], true);
@endphp

<div class="nav-item group relative flex items-center">
    <a
        href="{{ $item->url }}"
        class="nav-link-editorial {{ $isActive ? 'is-active' : '' }}"
        @if($isActive) aria-current="page" @endif
    >
        <span>{{ $item->label }}</span>
        <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </a>

    <div class="nav-dropdown {{ $alignEnd ? 'nav-dropdown--end' : '' }}">
        <div class="nav-dropdown-panel nav-mega-panel" style="--nav-mega-max-w: {{ $maxWidth }}; --nav-mega-cols: {{ $colCount }};">
            <div class="nav-mega-grid">
                @foreach($columns as $column)
                    <div class="nav-mega-col">
                        @if($column['title'])
                            <h4 class="nav-mega-title">{{ $column['title'] }}</h4>
                        @endif
                        <ul class="nav-mega-list">
                            @foreach($column['links'] as $link)
                                <li>
                                    <a
                                        href="{{ $link['url'] }}"
                                        class="nav-mega-link {{ !empty($link['active']) ? 'is-active' : '' }}"
                                        @if(!empty($link['active'])) aria-current="page" @endif
                                    >
                                        <span class="nav-mega-link__label">{{ $link['label'] }}</span>
                                        @if(!empty($link['desc']))
                                            <span class="nav-mega-link__desc">{{ $link['desc'] }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
