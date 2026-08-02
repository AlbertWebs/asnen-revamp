<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublishStatus;
use App\Http\Controllers\Admin\Concerns\NormalizesNullableIds;
use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartnerController extends Controller
{
    use NormalizesNullableIds;

    public function index(): View
    {
        abort_unless(auth()->user()?->can('partners.view'), 403);

        $partners = Partner::query()->orderBy('sort_order')->paginate(20);

        return view('admin.partners.index', compact('partners'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->can('partners.create'), 403);

        return view('admin.partners.edit', ['partner' => new Partner(['status' => PublishStatus::Draft])]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('partners.create'), 403);

        $this->nullEmptyIds($request, ['logo_id']);

        $partner = Partner::create($this->validatePartner($request));

        return redirect()->route('admin.partners.edit', $partner)->with('success', 'Partner created.');
    }

    public function edit(Partner $partner): View
    {
        abort_unless(auth()->user()?->can('partners.update'), 403);

        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner): RedirectResponse
    {
        abort_unless(auth()->user()?->can('partners.update'), 403);

        $this->nullEmptyIds($request, ['logo_id']);

        $partner->update($this->validatePartner($request, $partner));

        return back()->with('success', 'Partner updated.');
    }

    public function destroy(Partner $partner): RedirectResponse
    {
        abort_unless(auth()->user()?->can('partners.delete'), 403);

        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner deleted.');
    }

    public function publish(Partner $partner): RedirectResponse
    {
        abort_unless(auth()->user()?->can('partners.publish'), 403);

        $partner->publish();

        return back()->with('success', 'Partner published.');
    }

    public function unpublish(Partner $partner): RedirectResponse
    {
        abort_unless(auth()->user()?->can('partners.publish'), 403);

        $partner->unpublish();

        return back()->with('success', 'Partner unpublished.');
    }

    private function validatePartner(Request $request, ?Partner $partner = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:partners,slug,'.($partner?->id ?? 'NULL')],
            'description' => ['nullable', 'string'],
            'url' => ['nullable', 'url', 'max:500'],
            'logo_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'category' => ['nullable', 'string', 'max:100'],
            'partnership_start' => ['nullable', 'date'],
            'partnership_end' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer'],
            'verification_status' => ['nullable', 'string'],
        ]);
    }
}
