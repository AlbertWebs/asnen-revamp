<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegionController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->can('regions.view'), 403);

        $regions = Region::query()->orderBy('sort_order')->orderBy('name')->paginate(20);

        return view('admin.regions.index', compact('regions'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->can('regions.create'), 403);

        return view('admin.regions.edit', [
            'region' => new Region([
                'status' => PublishStatus::Draft,
                'country' => 'Kenya',
                'sort_order' => 0,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('regions.create'), 403);

        $region = Region::create($this->validateRegion($request));

        return redirect()->route('admin.regions.edit', $region)->with('success', 'Region created.');
    }

    public function edit(Region $region): View
    {
        abort_unless(auth()->user()?->can('regions.update'), 403);

        return view('admin.regions.edit', compact('region'));
    }

    public function update(Request $request, Region $region): RedirectResponse
    {
        abort_unless(auth()->user()?->can('regions.update'), 403);

        $region->update($this->validateRegion($request, $region));

        return back()->with('success', 'Region updated.');
    }

    public function destroy(Region $region): RedirectResponse
    {
        abort_unless(auth()->user()?->can('regions.delete'), 403);

        $region->delete();

        return redirect()->route('admin.regions.index')->with('success', 'Region deleted.');
    }

    public function publish(Region $region): RedirectResponse
    {
        abort_unless(auth()->user()?->can('regions.publish'), 403);

        $region->publish();

        return back()->with('success', 'Region published.');
    }

    public function unpublish(Region $region): RedirectResponse
    {
        abort_unless(auth()->user()?->can('regions.publish'), 403);

        $region->unpublish();

        return back()->with('success', 'Region unpublished.');
    }

    private function validateRegion(Request $request, ?Region $region = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:regions,slug,'.($region?->id ?? 'NULL')],
            'description' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'country' => ['nullable', 'string', 'max:120'],
            'impact_label' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'string', 'max:500'],
            'link_label' => ['nullable', 'string', 'max:120'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        return $validated;
    }
}
