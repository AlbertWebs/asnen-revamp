<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSite\Concerns\ResolvesPageBanners;
use App\Http\Controllers\PublicSite\Concerns\RespondsToAjaxForms;
use App\Http\Requests\PublicSite\EventRegistrationFormRequest;
use App\Mail\EventRegistrationSubmitted;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\MailLog;
use App\Models\ImpactStory;
use App\Models\Publication;
use App\Models\Webinar;
use App\Repositories\PageRepository;
use App\Services\HtmlSanitizer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class EventController extends Controller
{
    use ResolvesPageBanners;
    use RespondsToAjaxForms;

    public function __construct(
        private HtmlSanitizer $sanitizer,
        private PageRepository $pages,
    ) {}

    public function index()
    {
        $page = $this->pages->findBySlug('events-learning')
            ?? $this->pages->findBySlug('events');

        return view('public.events.index', [
            'page' => $page,
            'upcoming' => $this->upcomingQuery()->with('featuredImage')->limit(6)->get(),
            'past' => $this->pastQuery()->with('featuredImage')->limit(6)->get(),
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page),
        ]);
    }

    public function upcoming()
    {
        $page = $this->pages->findBySlug('events-learning-upcoming')
            ?? $this->pages->findBySlug('events-upcoming')
            ?? $this->pages->findBySlug('events-learning')
            ?? $this->pages->findBySlug('events');

        return view('public.events.upcoming', [
            'page' => $page,
            'ongoing' => $this->ongoingQuery()->with('featuredImage')->get(),
            'events' => $this->upcomingQuery()->with('featuredImage')->paginate(12),
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page),
        ]);
    }

    public function past()
    {
        $page = $this->pages->findBySlug('events-learning-past')
            ?? $this->pages->findBySlug('events-past')
            ?? $this->pages->findBySlug('events-learning')
            ?? $this->pages->findBySlug('events');

        return view('public.events.past', [
            'page' => $page,
            'events' => $this->pastQuery()->with('featuredImage')->paginate(12),
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page),
        ]);
    }

    public function webinars()
    {
        $page = $this->pages->findBySlug('events-learning-webinars')
            ?? $this->pages->findBySlug('events-webinars')
            ?? $this->pages->findBySlug('events-learning')
            ?? $this->pages->findBySlug('events');

        return view('public.events.webinars', [
            'page' => $page,
            'webinars' => Webinar::published()
                ->with('featuredImage')
                ->latest('held_at')
                ->paginate(12),
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page),
        ]);
    }

    public function ubuntuConference()
    {
        $page = $this->pages->findBySlug('events-learning-ubuntu-conference')
            ?? $this->pages->findBySlug('events-ubuntu-conference')
            ?? $this->pages->findBySlug('events-learning')
            ?? $this->pages->findBySlug('events');

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

        $event = $ubuntuEvents->first();

        return view('public.events.ubuntu-conference', [
            'page' => $page,
            'event' => $event,
            'ubuntuEvents' => $ubuntuEvents,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page, $event?->featuredImage),
        ]);
    }

    public function show(string $slug)
    {
        $event = Event::published()
            ->with('featuredImage')
            ->where('slug', $slug)
            ->firstOrFail();

        $page = $this->pages->findBySlug('events-learning')
            ?? $this->pages->findBySlug('events');

        $relatedPublication = Publication::published()
            ->with(['file', 'cover'])
            ->where(function ($query) use ($slug) {
                $query->where('slug', $slug)
                    ->orWhere('slug', $slug.'-materials');
            })
            ->first();

        $profile = $event->pageProfile();
        $companionSlug = $profile['companion_slug'] ?? null;
        $companionEvent = $companionSlug
            ? Event::published()->with('featuredImage')->where('slug', $companionSlug)->first()
            : null;

        $komolionStory = $profile
            ? ImpactStory::published()
                ->with('featuredImage')
                ->where('slug', ImpactStory::KOMOLION_SLUG)
                ->first()
            : null;

        return view('public.events.show', [
            'event' => $event,
            'page' => $page,
            'relatedPublication' => $relatedPublication,
            'companionEvent' => $companionEvent,
            'komolionStory' => $komolionStory,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page, $event->featuredImage),
        ]);
    }

    public function calendar(string $slug)
    {
        $event = Event::published()->where('slug', $slug)->firstOrFail();

        return response($event->toIcs(), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$event->slug.'.ics"',
        ]);
    }

    public function register(EventRegistrationFormRequest $request, string $slug)
    {
        $event = Event::published()->where('slug', $slug)->firstOrFail();

        if (! $event->acceptsRegistration()) {
            abort(403, 'Registration is closed for this event.');
        }

        $already = EventRegistration::query()
            ->where('event_id', $event->id)
            ->where('email', $request->validated('email'))
            ->exists();

        if ($already) {
            $message = 'You are already registered for this event. We will be in touch with details.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                ]);
            }

            return back()->with('success', $message);
        }

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

        try {
            Mail::send(new EventRegistrationSubmitted($event, $registration));
        } catch (Throwable $e) {
            MailLog::failLatest($e->getMessage());
            Log::error('Failed to send event registration notification email.', [
                'registration_id' => $registration->id,
                'event_id' => $event->id,
                'exception' => $e->getMessage(),
            ]);
        }

        $token = Str::random(64);
        session(['event_registration_token_'.$token => $registration->id]);

        return $this->formSuccessResponse(
            $request,
            $token,
            'event',
            'Your registration is confirmed. We will email you with joining details.',
            includeRedirect: false,
        );
    }

    protected function upcomingQuery()
    {
        return Event::published()
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at');
    }

    protected function ongoingQuery()
    {
        $now = now();

        return Event::published()
            ->where('starts_at', '<=', $now)
            ->where(function ($query) use ($now) {
                $query->where('ends_at', '>=', $now)
                    ->orWhere(function ($inner) use ($now) {
                        $inner->whereNull('ends_at')
                            ->whereDate('starts_at', $now->toDateString());
                    });
            })
            ->orderBy('starts_at');
    }

    protected function pastQuery()
    {
        $now = now();

        return Event::published()
            ->where('starts_at', '<', $now)
            ->where(function ($query) use ($now) {
                $query->where(function ($ended) use ($now) {
                    $ended->whereNotNull('ends_at')->where('ends_at', '<', $now);
                })->orWhere(function ($noEnd) use ($now) {
                    $noEnd->whereNull('ends_at')->where('starts_at', '<', $now->copy()->startOfDay());
                });
            })
            ->orderByDesc('starts_at');
    }
}
