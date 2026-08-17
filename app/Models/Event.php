<?php

namespace App\Models;

use App\Enums\VerificationStatus;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasSlug;
    use Publishable;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'summary',
        'body',
        'venue',
        'is_online',
        'online_url',
        'starts_at',
        'ends_at',
        'timezone',
        'capacity',
        'allow_registration',
        'registration_opens_at',
        'registration_closes_at',
        'featured_image_id',
        'status',
        'published_at',
        'verification_status',
    ];

    protected function casts(): array
    {
        return [
            'is_online' => 'boolean',
            'allow_registration' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'registration_opens_at' => 'datetime',
            'registration_closes_at' => 'datetime',
            'verification_status' => VerificationStatus::class,
            'capacity' => 'integer',
        ];
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'featured_image_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function isUpcoming(): bool
    {
        return $this->timingStatus() === 'upcoming';
    }

    public function isOngoing(): bool
    {
        return $this->timingStatus() === 'ongoing';
    }

    public function isPast(): bool
    {
        return $this->timingStatus() === 'past';
    }

    public function timingStatus(): string
    {
        if (! $this->starts_at) {
            return 'upcoming';
        }

        $now = now();
        $endsAt = $this->effectiveEndsAt();

        if ($this->starts_at->lte($now) && $endsAt->gte($now)) {
            return 'ongoing';
        }

        if ($this->starts_at->gt($now)) {
            return 'upcoming';
        }

        return 'past';
    }

    public function acceptsRegistration(): bool
    {
        if ($this->isPast()) {
            return false;
        }

        return (bool) $this->allow_registration;
    }

    public function timingLabel(): string
    {
        return match ($this->timingStatus()) {
            'ongoing' => 'Ongoing',
            'upcoming' => 'Upcoming',
            default => 'Past',
        };
    }

    public function effectiveEndsAt(): \Illuminate\Support\Carbon
    {
        return $this->ends_at
            ? $this->ends_at->copy()
            : $this->starts_at->copy()->endOfDay();
    }

    public function pageProfile(): ?array
    {
        if (! $this->slug) {
            return null;
        }

        $all = config('event_pages', []);
        $profile = is_array($all) ? ($all[$this->slug] ?? null) : null;

        return is_array($profile) ? $profile : null;
    }

    public function googleCalendarUrl(): ?string
    {
        if (! $this->starts_at) {
            return null;
        }

        $dates = $this->calendarUtcStart()->format('Ymd\THis\Z').'/'.$this->calendarUtcEnd()->format('Ymd\THis\Z');

        return 'https://calendar.google.com/calendar/render?'.http_build_query([
            'action' => 'TEMPLATE',
            'text' => $this->title,
            'dates' => $dates,
            'details' => strip_tags((string) ($this->summary ?? $this->body)),
            'location' => $this->is_online ? 'Online' : (string) $this->venue,
        ]);
    }

    public function outlookCalendarUrl(): ?string
    {
        if (! $this->starts_at) {
            return null;
        }

        return 'https://outlook.live.com/calendar/0/deeplink/compose?'.http_build_query([
            'path' => '/calendar/action/compose',
            'rru' => 'addevent',
            'subject' => $this->title,
            'startdt' => $this->calendarUtcStart()->format('Y-m-d\TH:i:s\Z'),
            'enddt' => $this->calendarUtcEnd()->format('Y-m-d\TH:i:s\Z'),
            'body' => strip_tags((string) ($this->summary ?? $this->body)),
            'location' => $this->is_online ? 'Online' : (string) $this->venue,
        ]);
    }

    public function toIcs(): string
    {
        $escape = static function (?string $value): string {
            $value = str_replace(['\\', ',', ';'], ['\\\\', '\\,', '\\;'], (string) $value);

            return str_replace(["\r\n", "\n"], '\\n', $value);
        };

        $uid = $this->slug.'@asnen';
        $stamp = now('UTC')->format('Ymd\THis\Z');
        $start = $this->calendarUtcStart()->format('Ymd\THis\Z');
        $end = $this->calendarUtcEnd()->format('Ymd\THis\Z');
        $location = $this->is_online ? 'Online' : (string) $this->venue;
        $description = strip_tags((string) ($this->summary ?? $this->body));

        return implode("\r\n", [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//ASNEN//Events//EN',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'BEGIN:VEVENT',
            'UID:'.$uid,
            'DTSTAMP:'.$stamp,
            'DTSTART:'.$start,
            'DTEND:'.$end,
            'SUMMARY:'.$escape($this->title),
            'DESCRIPTION:'.$escape($description),
            'LOCATION:'.$escape($location),
            'END:VEVENT',
            'END:VCALENDAR',
            '',
        ]);
    }

    protected function calendarUtcStart(): \Illuminate\Support\Carbon
    {
        return $this->starts_at->copy()->timezone('UTC');
    }

    protected function calendarUtcEnd(): \Illuminate\Support\Carbon
    {
        if ($this->ends_at) {
            return $this->ends_at->copy()->timezone('UTC');
        }

        return $this->starts_at->copy()->addHours(2)->timezone('UTC');
    }
}
