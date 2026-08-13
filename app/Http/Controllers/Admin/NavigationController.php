<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavigationItem;
use App\Models\NavigationMenu;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NavigationController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->can('navigation.view'), 403);

        $menus = NavigationMenu::query()->withCount('items')->get();

        return view('admin.navigation.index', compact('menus'));
    }

    public function edit(NavigationMenu $navigation): View
    {
        abort_unless(auth()->user()?->can('navigation.update'), 403);

        $pages = Page::query()->orderBy('title')->get(['id', 'title']);
        $items = $navigation->rootItems()->with('children')->get()->map(fn (NavigationItem $item) => $this->itemPayload($item))->values();

        return view('admin.navigation.edit', compact('navigation', 'pages', 'items'));
    }

    public function update(Request $request, NavigationMenu $navigation): RedirectResponse
    {
        abort_unless(auth()->user()?->can('navigation.update'), 403);

        $items = collect($request->input('items', []))->map(function ($item) {
            $item['page_id'] = filled($item['page_id'] ?? null) ? $item['page_id'] : null;
            $item['children'] = collect($item['children'] ?? [])->map(function ($child) {
                $child['page_id'] = filled($child['page_id'] ?? null) ? $child['page_id'] : null;

                return $child;
            })->all();

            return $item;
        })->all();
        $request->merge(['items' => $items]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'items' => ['nullable', 'array'],
            'items.*.label' => ['required', 'string', 'max:255'],
            'items.*.url' => ['nullable', 'string', 'max:500'],
            'items.*.page_id' => ['nullable', 'integer', 'exists:pages,id'],
            'items.*.sort_order' => ['nullable', 'integer'],
            'items.*.is_visible' => ['nullable', 'boolean'],
            'items.*.open_in_new_tab' => ['nullable', 'boolean'],
            'items.*.children' => ['nullable', 'array'],
            'items.*.children.*.label' => ['required', 'string', 'max:255'],
            'items.*.children.*.url' => ['nullable', 'string', 'max:500'],
            'items.*.children.*.page_id' => ['nullable', 'integer', 'exists:pages,id'],
            'items.*.children.*.sort_order' => ['nullable', 'integer'],
            'items.*.children.*.is_visible' => ['nullable', 'boolean'],
            'items.*.children.*.open_in_new_tab' => ['nullable', 'boolean'],
        ]);

        $navigation->update(['name' => $validated['name']]);

        NavigationItem::withTrashed()->where('menu_id', $navigation->id)->whereNotNull('parent_id')->forceDelete();
        NavigationItem::withTrashed()->where('menu_id', $navigation->id)->forceDelete();

        foreach ($validated['items'] ?? [] as $index => $item) {
            $parent = NavigationItem::create($this->itemAttributes($navigation->id, $item, $index));

            foreach ($item['children'] ?? [] as $childIndex => $child) {
                NavigationItem::create(array_merge(
                    $this->itemAttributes($navigation->id, $child, $childIndex),
                    ['parent_id' => $parent->id],
                ));
            }
        }

        return back()->with('success', 'Navigation updated.');
    }

    /**
     * @return array<string, mixed>
     */
    private function itemPayload(NavigationItem $item): array
    {
        return [
            'label' => $item->label,
            'url' => $item->url,
            'page_id' => $item->page_id ? (string) $item->page_id : '',
            'sort_order' => $item->sort_order,
            'is_visible' => $item->is_visible,
            'open_in_new_tab' => $item->open_in_new_tab,
            'children' => $item->children->map(fn (NavigationItem $child) => [
                'label' => $child->label,
                'url' => $child->url,
                'page_id' => $child->page_id ? (string) $child->page_id : '',
                'sort_order' => $child->sort_order,
                'is_visible' => $child->is_visible,
                'open_in_new_tab' => $child->open_in_new_tab,
            ])->values(),
        ];
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    private function itemAttributes(int $menuId, array $item, int $index): array
    {
        return [
            'menu_id' => $menuId,
            'label' => $item['label'],
            'url' => $item['url'] ?? null,
            'page_id' => $item['page_id'] ?: null,
            'sort_order' => $item['sort_order'] ?? $index,
            'is_visible' => (bool) (int) ($item['is_visible'] ?? 1),
            'open_in_new_tab' => (bool) (int) ($item['open_in_new_tab'] ?? 0),
        ];
    }
}
