<?php

namespace App\Models;

use App\Enums\VerificationStatus;
use App\Models\Concerns\HasRevisions;
use App\Models\Concerns\HasSeoMeta;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use App\Models\Concerns\RequiresSafeguarding;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Page extends Model
{
    use HasRevisions;
    use HasSeoMeta;
    use HasSlug;
    use LogsActivity;
    use Publishable;
    use RequiresSafeguarding;
    use SoftDeletes;

    /**
     * Archived pages whose copy still appears on a merged public page.
     *
     * @var array<string, string>
     */
    public const MERGED_DESTINATIONS = [
        'about-governance' => 'Leadership & Governance',
        'about-mission-vision-values' => 'Who We Are',
        'about-our-story' => 'Who We Are',
        'impact-komolion' => 'Success Stories',
        'impact-reports' => 'Reports & Publications',
    ];

    protected $fillable = [
        'title',
        'slug',
        'template',
        'excerpt',
        'banner_image_ids',
        'status',
        'published_at',
        'scheduled_at',
        'unpublished_at',
        'timezone',
        'requires_safeguarding',
        'safeguarding_status',
        'verification_status',
        'author_id',
        'editor_notes',
    ];

    protected function casts(): array
    {
        return [
            'unpublished_at' => 'datetime',
            'verification_status' => VerificationStatus::class,
            'banner_image_ids' => 'array',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(PageBlock::class)->orderBy('sort_order');
    }

    public function mergedDestinationLabel(): ?string
    {
        return self::MERGED_DESTINATIONS[$this->slug] ?? null;
    }

    public function publicPath(): string
    {
        return match ($this->slug) {
            'home' => '/',
            'gallery' => '/resources/gallery',
            'impact-reports' => '/resources/publications',
            'leadership-governance' => '/about/leadership',
            'vision-mission-values' => '/about/who-we-are#vision',
            default => $this->publicPathFromSlug(),
        };
    }

    /**
     * Group pages in the same order as the public header and footer.
     *
     * @param  \Illuminate\Support\Collection<int, self>  $pages
     * @return array<int, array{label: string, rows: list<array{page: self, depth: int}>}>
     */
    public static function groupedForAdmin(\Illuminate\Support\Collection $pages): array
    {
        $bySlug = $pages->keyBy('slug');
        $used = [];

        $take = function (array $entry, int $depth) use (&$take, &$used, $bySlug): array {
            $page = $bySlug->get($entry['slug'] ?? '');
            if (! $page) {
                foreach ($entry['aliases'] ?? [] as $alias) {
                    $page = $bySlug->get($alias);
                    if ($page) {
                        break;
                    }
                }
            }

            $rows = [];
            if ($page && ! isset($used[$page->id])) {
                $used[$page->id] = true;
                $rows[] = ['page' => $page, 'depth' => $depth];
            }

            foreach ($entry['children'] ?? [] as $child) {
                $rows = array_merge($rows, $take($child, $page ? $depth + 1 : $depth));
            }

            return $rows;
        };

        $sections = [];
        foreach (self::adminSitemap() as $section) {
            $rows = [];
            foreach ($section['entries'] as $entry) {
                $rows = array_merge($rows, $take($entry, 0));
            }
            $sections[$section['label']] = $rows;
        }

        $prefixSections = [
            'home' => 'Home',
            'about' => 'About',
            'what-we-do' => 'What We Do',
            'impact' => 'Impact',
            'events-learning' => 'Events & Learning',
            'resources' => 'Resources',
            'gallery' => 'Resources',
            'get-involved' => 'Get Involved',
            'contact' => 'Contact',
            'accessibility' => 'Policies',
            'privacy' => 'Policies',
            'terms' => 'Policies',
            'cookies' => 'Policies',
            'faqs' => 'Policies',
            'safeguarding' => 'Policies',
        ];

        foreach ($pages->sortBy('title', SORT_NATURAL | SORT_FLAG_CASE) as $page) {
            if (isset($used[$page->id])) {
                continue;
            }

            $section = 'Other';
            foreach ($prefixSections as $prefix => $label) {
                if ($page->slug === $prefix || str_starts_with($page->slug, $prefix.'-')) {
                    $section = $label;
                    break;
                }
            }

            $used[$page->id] = true;
            if (! array_key_exists($section, $sections)) {
                $sections[$section] = [];
            }
            $sections[$section][] = ['page' => $page, 'depth' => 0];
        }

        return collect($sections)
            ->filter(fn (array $rows) => $rows !== [])
            ->map(fn (array $rows, string $label) => ['label' => $label, 'rows' => $rows])
            ->values()
            ->all();
    }

    /**
     * @return list<array{label: string, entries: list<array<string, mixed>>}>
     */
    public static function adminSitemap(): array
    {
        return [
            [
                'label' => 'Home',
                'entries' => [
                    ['slug' => 'home'],
                ],
            ],
            [
                'label' => 'About',
                'entries' => [
                    [
                        'slug' => 'about-who-we-are',
                        'children' => [
                            ['slug' => 'about-mission-vision-values'],
                            ['slug' => 'about-our-story'],
                        ],
                    ],
                    [
                        'slug' => 'about-leadership',
                        'aliases' => ['leadership-governance'],
                        'children' => [
                            ['slug' => 'about-governance'],
                        ],
                    ],
                    ['slug' => 'about-partners'],
                ],
            ],
            [
                'label' => 'What We Do',
                'entries' => [
                    [
                        'slug' => 'what-we-do',
                        'children' => [
                            ['slug' => 'what-we-do-inclusive-education'],
                            ['slug' => 'what-we-do-caregiver-training'],
                            ['slug' => 'what-we-do-early-identification-intervention'],
                            ['slug' => 'what-we-do-disability-awareness-advocacy'],
                            ['slug' => 'what-we-do-social-inclusion'],
                            ['slug' => 'what-we-do-research-policy-partnerships'],
                            ['slug' => 'what-we-do-community-outreach-medical-camps'],
                        ],
                    ],
                ],
            ],
            [
                'label' => 'Impact',
                'entries' => [
                    [
                        'slug' => 'impact',
                        'children' => [
                            [
                                'slug' => 'impact-stories',
                                'children' => [
                                    ['slug' => 'impact-komolion'],
                                ],
                            ],
                            ['slug' => 'impact-reports'],
                            ['slug' => 'impact-regions'],
                        ],
                    ],
                ],
            ],
            [
                'label' => 'Events & Learning',
                'entries' => [
                    [
                        'slug' => 'events-learning',
                        'children' => [
                            ['slug' => 'events-learning-upcoming', 'aliases' => ['events-upcoming']],
                            ['slug' => 'events-learning-past', 'aliases' => ['events-past']],
                            ['slug' => 'events-learning-webinars', 'aliases' => ['events-webinars']],
                            ['slug' => 'events-learning-ubuntu-conference', 'aliases' => ['events-ubuntu-conference']],
                        ],
                    ],
                ],
            ],
            [
                'label' => 'Resources',
                'entries' => [
                    [
                        'slug' => 'resources',
                        'children' => [
                            ['slug' => 'resources-publications'],
                            ['slug' => 'resources-toolkits'],
                            ['slug' => 'resources-webinars'],
                            ['slug' => 'resources-news'],
                            ['slug' => 'gallery', 'aliases' => ['resources-gallery']],
                        ],
                    ],
                ],
            ],
            [
                'label' => 'Get Involved',
                'entries' => [
                    [
                        'slug' => 'get-involved',
                        'children' => [
                            ['slug' => 'get-involved-membership'],
                            ['slug' => 'get-involved-volunteer'],
                            ['slug' => 'get-involved-partner'],
                            ['slug' => 'get-involved-donate'],
                        ],
                    ],
                ],
            ],
            [
                'label' => 'Contact',
                'entries' => [
                    ['slug' => 'contact'],
                ],
            ],
            [
                'label' => 'Policies',
                'entries' => [
                    ['slug' => 'accessibility'],
                    ['slug' => 'privacy'],
                    ['slug' => 'terms'],
                    ['slug' => 'cookies'],
                    ['slug' => 'faqs'],
                    ['slug' => 'safeguarding'],
                ],
            ],
        ];
    }

    private function publicPathFromSlug(): string
    {
        foreach (['what-we-do', 'events-learning', 'get-involved', 'resources', 'about', 'impact'] as $section) {
            if ($this->slug === $section) {
                return '/'.$section;
            }
            if (str_starts_with($this->slug, $section.'-')) {
                return '/'.$section.'/'.substr($this->slug, strlen($section) + 1);
            }
        }

        return '/'.$this->slug;
    }

    public function mergedSourceNote(): ?string
    {
        return match ($this->slug) {
            'about-who-we-are' => 'Vision, mission, values, and our story still come from the archived Vision and Our Story pages. Edit those pages to change those sections on the public Who We Are page.',
            'about-leadership', 'leadership-governance' => 'Governance copy still comes from the archived Governance page and appears on the public Leadership & Governance page.',
            'impact-stories' => 'Komolion is managed as a success story. Edit it under Success Stories rather than as a separate menu page.',
            'impact-reports' => 'Impact reports now share the Reports & Publications page. Edit that page for intro copy, and manage files under Publications.',
            'resources' => 'This is the Resources hub. Child pages cover publications, toolkits, webinars, news, and the gallery.',
            'resources-publications' => 'This page lists reports and publications. Add or replace PDFs under Publications in the admin sidebar.',
            'resources-toolkits' => 'Toolkit files are managed under Publications. This page controls the public Toolkits intro.',
            'resources-webinars' => 'Individual recordings are managed under Webinars. This page controls the public library intro.',
            'resources-news' => 'Individual articles are managed under Articles. This page controls the public News intro.',
            'gallery' => 'Albums are managed under Galleries. This page controls the public Gallery intro.',
            default => null,
        };
    }

    /**
     * Ordered banner images for about / interior heroes.
     *
     * @return \Illuminate\Support\Collection<int, MediaAsset>
     */
    public function bannerImages()
    {
        $ids = collect($this->banner_image_ids ?? [])
            ->filter(fn ($id) => filled($id) && $id !== 'null')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return collect();
        }

        $byId = MediaAsset::query()
            ->whereIn('id', $ids->all())
            ->get()
            ->keyBy('id');

        return $ids
            ->map(fn (int $id) => $byId->get($id))
            ->filter()
            ->values();
    }
}
