<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Models\ImpactMetric;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImpactMetricController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->can('impact_metrics.view'), 403);

        $metrics = ImpactMetric::query()->with('program')->latest()->paginate(20);

        return view('admin.impact-metrics.index', compact('metrics'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->can('impact_metrics.create'), 403);

        return view('admin.impact-metrics.edit', [
            'metric' => new ImpactMetric(['status' => PublishStatus::Draft]),
            'programs' => Program::query()->orderBy('title')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('impact_metrics.create'), 403);

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'numeric_value' => ['nullable', 'numeric'],
            'unit' => ['nullable', 'string', 'max:50'],
            'qualifier' => ['nullable', 'string', 'max:255'],
            'source_label' => ['nullable', 'string', 'max:255'],
            'as_of_date' => ['nullable', 'date'],
            'region' => ['nullable', 'string', 'max:255'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'public_label' => ['nullable', 'string', 'max:255'],
            'verification_status' => ['nullable', 'string'],
        ]);

        $metric = ImpactMetric::create($validated);

        return redirect()->route('admin.impact-metrics.edit', $metric)->with('success', 'Metric created.');
    }

    public function edit(ImpactMetric $impactMetric): View
    {
        abort_unless(auth()->user()?->can('impact_metrics.update'), 403);

        return view('admin.impact-metrics.edit', [
            'metric' => $impactMetric,
            'programs' => Program::query()->orderBy('title')->get(),
        ]);
    }

    public function update(Request $request, ImpactMetric $impactMetric): RedirectResponse
    {
        abort_unless(auth()->user()?->can('impact_metrics.update'), 403);

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'numeric_value' => ['nullable', 'numeric'],
            'unit' => ['nullable', 'string', 'max:50'],
            'qualifier' => ['nullable', 'string', 'max:255'],
            'source_label' => ['nullable', 'string', 'max:255'],
            'as_of_date' => ['nullable', 'date'],
            'region' => ['nullable', 'string', 'max:255'],
            'program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'public_label' => ['nullable', 'string', 'max:255'],
            'verification_status' => ['nullable', 'string'],
        ]);

        $impactMetric->update($validated);

        return back()->with('success', 'Metric updated.');
    }

    public function destroy(ImpactMetric $impactMetric): RedirectResponse
    {
        abort_unless(auth()->user()?->can('impact_metrics.delete'), 403);

        $impactMetric->delete();

        return redirect()->route('admin.impact-metrics.index')->with('success', 'Metric deleted.');
    }

    public function publish(ImpactMetric $impactMetric): RedirectResponse
    {
        abort_unless(auth()->user()?->can('impact_metrics.publish'), 403);

        $impactMetric->publish();

        return back()->with('success', 'Metric published.');
    }

    public function unpublish(ImpactMetric $impactMetric): RedirectResponse
    {
        abort_unless(auth()->user()?->can('impact_metrics.publish'), 403);

        $impactMetric->unpublish();

        return back()->with('success', 'Metric unpublished.');
    }
}
