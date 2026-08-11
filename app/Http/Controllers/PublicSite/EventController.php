<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSite\Concerns\ResolvesPageBanners;
use App\Http\Controllers\PublicSite\Concerns\RespondsToAjaxForms;
use App\Http\Requests\PublicSite\EventRegistrationFormRequest;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Publication;
use App\Models\Webinar;
use App\Repositories\PageRepository;
use App\Services\HtmlSanitizer;
use Illuminate\Support\Str;

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
        $page = $this->pages->findBySlug('events-upcoming')
            ?? $this->pages->findBySlug('events-learning')
            ?? $this->pages->findBySlug('events');

        return view('public.events.upcoming', [
            'page' => $page,
            'events' => $this->upcomingQuery()->with('featuredImage')->paginate(12),
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page),
        ]);
    }

    public function past()
    {
        $page = $this->pages->findBySlug('events-past')
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
        $page = $this->pages->findBySlug('events-webinars')
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
        $page = $this->pages->findBySlug('events-ubuntu-conference')
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
                    ->orWhere('slug', $slug.'-materials')
                    ->orWhere('slug', 'saacs-asnen-aac');
            })
            ->first();

        return view('public.events.show', [
            'event' => $event,
            'page' => $page,
            'relatedPublication' => $relatedPublication,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page, $event->featuredImage),
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

        return $this->formSuccessResponse(
            $request,
            $token,
            'event',
            'Your event registration has been received. We will be in touch with details.'
        );
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
