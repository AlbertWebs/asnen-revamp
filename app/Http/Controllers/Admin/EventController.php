<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublishStatus;
use App\Http\Controllers\Admin\Concerns\NormalizesNullableIds;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    use NormalizesNullableIds;

    public function index(): View
    {
        abort_unless(auth()->user()?->can('events.view'), 403);

        $events = Event::query()->orderBy('starts_at')->get();

        $ongoing = $events
            ->filter(fn (Event $event) => $event->isOngoing())
            ->sortBy(fn (Event $event) => $event->effectiveEndsAt())
            ->values();

        $upcoming = $events
            ->filter(fn (Event $event) => $event->isUpcoming())
            ->sortBy('starts_at')
            ->values();

        $past = $events
            ->filter(fn (Event $event) => $event->isPast())
            ->sortByDesc('starts_at')
            ->values();

        return view('admin.events.index', compact('ongoing', 'upcoming', 'past'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->can('events.create'), 403);

        return view('admin.events.edit', ['event' => new Event(['status' => PublishStatus::Draft, 'timezone' => 'Africa/Nairobi'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('events.create'), 403);

        $this->nullEmptyIds($request, ['featured_image_id']);
        $validated = $this->validateEvent($request);
        $event = Event::create($validated);

        return redirect()->route('admin.events.edit', $event)->with('success', 'Event created.');
    }

    public function edit(Event $event): View
    {
        abort_unless(auth()->user()?->can('events.update'), 403);

        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        abort_unless(auth()->user()?->can('events.update'), 403);

        $this->nullEmptyIds($request, ['featured_image_id']);
        $event->update($this->validateEvent($request, $event));

        return back()->with('success', 'Event updated.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        abort_unless(auth()->user()?->can('events.delete'), 403);

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted.');
    }

    public function publish(Event $event): RedirectResponse
    {
        abort_unless(auth()->user()?->can('events.publish'), 403);

        $event->publish();

        return back()->with('success', 'Event published.');
    }

    public function unpublish(Event $event): RedirectResponse
    {
        abort_unless(auth()->user()?->can('events.publish'), 403);

        $event->unpublish();

        return back()->with('success', 'Event unpublished.');
    }

    private function validateEvent(Request $request, ?Event $event = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:events,slug,'.($event?->id ?? 'NULL')],
            'type' => ['nullable', 'string', 'max:50'],
            'summary' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'venue' => ['nullable', 'string', 'max:255'],
            'is_online' => ['nullable', 'boolean'],
            'online_url' => ['nullable', 'string', 'max:500', new \App\Rules\PublicUrlOrPath],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'timezone' => ['nullable', 'string', 'max:50'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'featured_image_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'verification_status' => ['nullable', 'string'],
        ]);

        $validated['is_online'] = $request->boolean('is_online');

        return $validated;
    }
}
