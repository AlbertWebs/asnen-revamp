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

        $navigation->load('items');
        $pages = Page::query()->orderBy('title')->get(['id', 'title']);

        return view('admin.navigation.edit', compact('navigation', 'pages'));
    }

    public function update(Request $request, NavigationMenu $navigation): RedirectResponse
    {
        abort_unless(auth()->user()?->can('navigation.update'), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'items' => ['nullable', 'array'],
            'items.*.label' => ['required_with:items', 'string', 'max:255'],
            'items.*.url' => ['nullable', 'string', 'max:500'],
            'items.*.page_id' => ['nullable', 'integer', 'exists:pages,id'],
            'items.*.sort_order' => ['nullable', 'integer'],
            'items.*.is_visible' => ['nullable', 'boolean'],
            'items.*.open_in_new_tab' => ['nullable', 'boolean'],
        ]);

        $navigation->update(['name' => $validated['name']]);

        $navigation->items()->delete();

        foreach ($validated['items'] ?? [] as $index => $item) {
            NavigationItem::create([
                'menu_id' => $navigation->id,
                'label' => $item['label'],
                'url' => $item['url'] ?? null,
                'page_id' => $item['page_id'] ?? null,
                'sort_order' => $item['sort_order'] ?? $index,
                'is_visible' => (bool) ($item['is_visible'] ?? true),
                'open_in_new_tab' => (bool) ($item['open_in_new_tab'] ?? false),
            ]);
        }

        return back()->with('success', 'Navigation updated.');
    }
}
