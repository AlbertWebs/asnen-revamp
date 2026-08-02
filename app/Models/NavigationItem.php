<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class NavigationItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'label',
        'url',
        'page_id',
        'route_name',
        'sort_order',
        'is_visible',
        'open_in_new_tab',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'open_in_new_tab' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(NavigationMenu::class, 'menu_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(NavigationItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(NavigationItem::class, 'parent_id')->orderBy('sort_order');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Whether the current request matches this item or any of its children.
     */
    public function isActive(?string $requestPath = null): bool
    {
        $path = '/'.trim($requestPath ?? request()->path(), '/');
        if ($path === '//') {
            $path = '/';
        }

        $candidates = collect([$this->url]);

        if ($this->relationLoaded('children')) {
            $candidates = $candidates->merge($this->children->pluck('url'));
        }

        foreach ($candidates->filter() as $url) {
            $normalized = $this->normalizeNavPath((string) $url);

            if ($normalized === '/') {
                if ($path === '/') {
                    return true;
                }

                continue;
            }

            if ($path === $normalized || str_starts_with($path, $normalized.'/')) {
                return true;
            }
        }

        return false;
    }

    public function childIsActive(string $url, ?string $requestPath = null): bool
    {
        $path = '/'.trim($requestPath ?? request()->path(), '/');
        if ($path === '/' || $path === '//') {
            $path = '/';
        } elseif (! str_starts_with($path, '/')) {
            $path = '/'.$path;
        }

        $normalized = $this->normalizeNavPath($url);

        if ($normalized === '/') {
            return $path === '/';
        }

        if ($path === $normalized) {
            return true;
        }

        // Same URL as the parent section hub: exact match only (avoids /impact
        // lighting up for every /impact/* sibling page).
        if ($normalized === $this->normalizeNavPath((string) $this->url)) {
            return false;
        }

        return str_starts_with($path, $normalized.'/');
    }

    protected function normalizeNavPath(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        $path = is_string($path) && $path !== '' ? $path : $url;
        $path = '/'.trim($path, '/');

        return $path === '/' || $path === '//' ? '/' : $path;
    }
}
