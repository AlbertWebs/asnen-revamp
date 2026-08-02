<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicSite\EventRegistrationFormRequest;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Webinar;
use App\Services\HtmlSanitizer;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function __construct(
        private HtmlSanitizer $sanitizer,
    ) {}

    public function index()
    {
        return view('public.events.index', [
            'upcoming' => $this->upcomingQuery()->with('featuredImage')->limit(6)->get(),
            'past' => $this->pastQuery()->with('featuredImage')->limit(6)->get(),
            'sanitizer' => $this->sanitizer,
        ]);
    }

    public function upcoming()
    {
        return view('public.events.upcoming', [
            'events' => $this->upcomingQuery()->with('featuredImage')->paginate(12),
            'sanitizer' => $this->sanitizer,
        ]);
    }

    public function past()
    {
        return view('public.events.past', [
            'events' => $this->pastQuery()->with('featuredImage')->paginate(12),
            'sanitizer' => $this->sanitizer,
        ]);
    }

    public function webinars()
    {
        return view('public.events.webinars', [
            'webinars' => Webinar::published()
                ->with('featuredImage')
                ->latest('held_at')
                ->paginate(12),
            'sanitizer' => $this->sanitizer,
        ]);
    }

    public function ubuntuConference()
    {
        $ubuntuEvents = Event::published()
            ->with('featuredImage')
            ->where(function ($query) {
                $query->where('type', 'conference')
                    ->where(function ($inner) {
                        $inner->where('slug', 'like', '%ubuntu%')
                            ->orWhere('title', 'like', '%Ubuntu%');
                    });
            })
            ->orderByDesc('starts_at')
            ->get();

        return view('public.events.ubuntu-conference', [
            'event' => $ubuntuEvents->first(),
            'ubuntuEvents' => $ubuntuEvents,
            'sanitizer' => $this->sanitizer,
        ]);
    }

    public function show(string $slug)
    {
        $event = Event::published()
            ->with('featuredImage')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('public.events.show', [
            'event' => $event,
            'sanitizer' => $this->sanitizer,
        ]);
    }

    public function register(EventRegistrationFormRequest $request, string $slug)
    {
        $event = Event::published()->where('slug', $slug)->firstOrFail();

        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'phone' => $request->validated('phone'),
            'organization' => $request->validated('organization'),
            'notes' => $request->validated('notes'),
            'consent_marketing' => $request->boolean('consent_marketing'),
            'status' => 'confirmed',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $token = Str::random(64);
        session(['event_registration_token_'.$token => $registration->id]);

        return redirect()->route('site.forms.confirmation', ['token' => $token, 'type' => 'event']);
    }

    protected function upcomingQuery()
    {
        return Event::published()
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at');
    }

    protected function pastQuery()
    {
        return Event::published()
            ->where('starts_at', '<', now())
            ->orderByDesc('starts_at');
    }
}
