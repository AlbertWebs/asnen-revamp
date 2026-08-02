<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Models\DonationCampaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonationCampaignController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->can('donations.view'), 403);

        $campaigns = DonationCampaign::query()->latest()->paginate(20);

        return view('admin.donation-campaigns.index', compact('campaigns'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->can('donations.create'), 403);

        return view('admin.donation-campaigns.edit', [
            'campaign' => new DonationCampaign(['status' => PublishStatus::Draft, 'currency' => 'KES']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('donations.create'), 403);

        $campaign = DonationCampaign::create($this->validateCampaign($request));

        return redirect()->route('admin.donation-campaigns.edit', $campaign)->with('success', 'Campaign created.');
    }

    public function edit(DonationCampaign $donationCampaign): View
    {
        abort_unless(auth()->user()?->can('donations.update'), 403);

        return view('admin.donation-campaigns.edit', ['campaign' => $donationCampaign]);
    }

    public function update(Request $request, DonationCampaign $donationCampaign): RedirectResponse
    {
        abort_unless(auth()->user()?->can('donations.update'), 403);

        $donationCampaign->update($this->validateCampaign($request));

        return back()->with('success', 'Campaign updated.');
    }

    public function destroy(DonationCampaign $donationCampaign): RedirectResponse
    {
        abort_unless(auth()->user()?->can('donations.delete'), 403);

        $donationCampaign->delete();

        return redirect()->route('admin.donation-campaigns.index')->with('success', 'Campaign deleted.');
    }

    public function publish(DonationCampaign $donationCampaign): RedirectResponse
    {
        abort_unless(auth()->user()?->can('donations.publish'), 403);

        $donationCampaign->publish();

        return back()->with('success', 'Campaign published.');
    }

    public function unpublish(DonationCampaign $donationCampaign): RedirectResponse
    {
        abort_unless(auth()->user()?->can('donations.publish'), 403);

        $donationCampaign->unpublish();

        return back()->with('success', 'Campaign unpublished.');
    }

    private function validateCampaign(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'goal_amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
        ]);
    }
}
