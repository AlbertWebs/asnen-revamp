<?php

namespace Database\Seeders;

use App\Enums\ConsentStatus;
use App\Enums\PublishStatus;
use App\Enums\SafeguardingStatus;
use App\Enums\VerificationStatus;
use App\Models\Announcement;
use App\Models\Article;
use App\Models\DonationCampaign;
use App\Models\Event;
use App\Models\Faq;
use App\Models\FormDefinition;
use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\ImpactMetric;
use App\Models\ImpactStory;
use App\Models\MediaAsset;
use App\Models\MembershipPlan;
use App\Models\Page;
use App\Models\PageBlock;
use App\Models\Partner;
use App\Models\Program;
use App\Models\Publication;
use App\Models\Redirect;
use App\Models\Region;
use App\Models\StoryOutcome;
use App\Models\TeamMember;
use App\Models\Webinar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $programs = $this->seedPrograms();
        $partners = $this->seedPartners();
        $this->seedTeamMembers();
        $this->seedEvents();
        $this->seedGalleries();
        $metricIds = $this->seedImpactMetrics();
        $webinars = $this->seedWebinars();
        $komolion = $this->seedKomolionStory($programs, $partners);
        $this->seedPublications();
        $this->seedPages($metricIds, $komolion, $programs);
        $this->seedFaqs();
        $this->seedFormDefinitions();
        $this->seedDonationCampaign();
        $this->seedRedirects();
        $this->seedMembershipPlans();
        $this->seedAnnouncement();
        $this->seedRegions();
        $this->seedArticles();
    }

    /** @return array<string, Program> */
    private function seedPrograms(): array
    {
        $definitions = [
            [
                'title' => 'Inclusive Education',
                'slug' => 'inclusive-education',
                'summary' => 'Building African homegrown models that help schools, educators, and communities welcome every learner with dignity and high expectations.',
                'body' => '<p>ASNEN supports inclusive education through teacher training, classroom practice, and collaboration with schools and policymakers. We champion approaches that respect neurodiversity, learning differences, and diverse support needs-ensuring no child is left behind.</p>',
                'sort_order' => 1,
            ],
            [
                'title' => 'Caregiver Training',
                'slug' => 'caregiver-training',
                'summary' => 'Strengthening families and caregivers with practical skills, peer support, and information to nurture children and young adults with special needs.',
                'body' => '<p>Our caregiver training programs equip parents, guardians, and professional caregivers with evidence-informed strategies, community connections, and ongoing support frameworks grounded in compassion and reciprocity.</p>',
                'sort_order' => 2,
            ],
            [
                'title' => 'Early Identification & Intervention',
                'slug' => 'early-identification-intervention',
                'summary' => 'Promoting timely identification and responsive intervention so children receive support when it can make the greatest difference.',
                'body' => '<p>ASNEN works with communities, schools, and health partners to improve pathways to assessment, referral, and early support-reducing delays that limit opportunity and inclusion.</p>',
                'sort_order' => 3,
            ],
            [
                'title' => 'Disability Awareness & Advocacy',
                'slug' => 'disability-awareness-advocacy',
                'summary' => 'Raising awareness, challenging stigma, and advancing rights-based advocacy for persons with disabilities across education and community life.',
                'body' => '<p>Through workshops, campaigns, and coalition building, ASNEN amplifies the principle that nothing about us without us-centering lived experience in advocacy for inclusive policy and practice.</p>',
                'sort_order' => 4,
            ],
            [
                'title' => 'Social Inclusion',
                'slug' => 'social-inclusion',
                'summary' => 'Fostering belonging through community activities, peer connection, and programs that celebrate diversity and shared humanity.',
                'body' => '<p>Social inclusion initiatives create spaces where children and young adults with disabilities participate fully in community life-building friendships, confidence, and mutual understanding rooted in UBUNTU.</p>',
                'sort_order' => 5,
            ],
            [
                'title' => 'Research, Policy & Partnerships',
                'slug' => 'research-policy-partnerships',
                'summary' => 'Connecting African evidence, policy dialogue, and strategic partnerships to advance inclusive education systems.',
                'body' => '<p>ASNEN collaborates with researchers, institutions, and networks to generate context-relevant knowledge, inform policy, and build sustainable partnerships for inclusion at scale.</p>',
                'sort_order' => 6,
            ],
            [
                'title' => 'Community Outreach & Medical Camps',
                'slug' => 'community-outreach-medical-camps',
                'summary' => 'Bringing assessment, registration, and specialist support to underserved communities through coordinated outreach and medical camps.',
                'body' => '<p>Community outreach and medical camps-such as the Komolion initiative in Baringo County-extend pathways to registration, assessment, and surgical referral while combating stigma and raising local awareness.</p>',
                'sort_order' => 7,
            ],
        ];

        $programs = [];
        foreach ($definitions as $definition) {
            $programs[$definition['slug']] = Program::updateOrCreate(
                ['slug' => $definition['slug']],
                [
                    ...$definition,
                    'status' => PublishStatus::Published,
                    'published_at' => now(),
                    'verification_status' => VerificationStatus::Verified,
                ]
            );
        }

        return $programs;
    }

    /** @return array<string, Partner> */
    private function seedPartners(): array
    {
        // Sourced from https://asnenafrica.org/about.html collaborators carousel
        $definitions = [
            ['name' => 'Kenya Private Schools Association', 'slug' => 'kpsa', 'description' => 'KPSA', 'logo' => 'partners/kpsa.jpg', 'publish' => true],
            ['name' => 'Beyond Zero', 'slug' => 'beyond-zero', 'description' => 'Beyond Zero', 'logo' => 'partners/beyond-zero.jpg', 'publish' => true],
            ['name' => 'National Council for Persons with Disabilities', 'slug' => 'ncpwd', 'description' => 'NCPWD', 'logo' => 'partners/ncpwd.jpg', 'publish' => true],
            ['name' => 'Ministry of Education', 'slug' => 'ministry-of-education', 'description' => 'Government of Kenya', 'logo' => 'partners/ministry-of-education.jpg', 'publish' => true],
            ['name' => 'Ministry of Health', 'slug' => 'ministry-of-health', 'description' => 'Government of Kenya', 'logo' => 'partners/ministry-of-health.jpg', 'publish' => true],
            ['name' => 'EU Humanitarian Aid and Civil Protection', 'slug' => 'eu-humanitarian', 'description' => 'European Union', 'logo' => 'partners/eu-humanitarian.jpg', 'publish' => true],
            ['name' => 'The Lutheran World Federation', 'slug' => 'lutheran-world-federation', 'description' => 'Lutheran', 'logo' => 'partners/lutheran-world-federation.jpg', 'publish' => true],
            ['name' => 'Sense International', 'slug' => 'sense-international', 'description' => 'Sense', 'logo' => 'partners/sense-international.jpg', 'publish' => true],
            ['name' => 'Humanity Inclusion', 'slug' => 'humanity-inclusion', 'description' => 'Humanity Inclusion', 'logo' => 'partners/humanity-inclusion.jpg', 'publish' => true],
            ['name' => 'Acorn Special Tutorials', 'slug' => 'acorn-special-tutorials', 'description' => 'ACORN', 'logo' => 'partners/acorn.jpg', 'publish' => true],
            ['name' => 'Cooperative Insurance of Kenya', 'slug' => 'cic-group', 'description' => 'CIC Group', 'logo' => 'partners/cic-group.jpg', 'publish' => true],
            ['name' => 'Mastercard Foundation', 'slug' => 'mastercard-foundation', 'description' => 'Mastercard Foundation', 'logo' => 'partners/mastercard-foundation.jpg', 'publish' => true],
            ['name' => 'Action Foundation', 'slug' => 'action-foundation', 'description' => 'Action Foundation', 'logo' => 'partners/action-foundation.jpg', 'publish' => true],
            ['name' => 'Cookies House of Designs', 'slug' => 'cookies-house-of-designs', 'description' => 'CHD', 'logo' => 'partners/chd.jpg', 'publish' => true],
            ['name' => 'Kenya Institute of Curriculum Development', 'slug' => 'kenya-institute-of-curriculum-development', 'description' => 'As listed on asnenafrica.org', 'logo' => 'partners/kise-kicd.jpg', 'publish' => true],
            ['name' => 'Collaborative Action for Childcare', 'slug' => 'collaborative-action-for-childcare', 'description' => 'CAC', 'logo' => 'partners/cac.jpg', 'publish' => true],
            ['name' => 'Uthabiti', 'slug' => 'uthabiti', 'description' => 'As listed on asnenafrica.org collaborators', 'logo' => 'partners/uthabiti.jpg', 'publish' => true],
            ['name' => 'The Aga Khan University Hospital', 'slug' => 'aga-khan-university-hospital', 'description' => 'Aga Khan', 'logo' => 'partners/aga-khan.jpg', 'publish' => true],
            // Komolion report partners (draft linkage only)
            ['name' => 'Noogera Foundation', 'slug' => 'noogera-foundation', 'description' => null, 'logo' => null, 'publish' => false],
            ['name' => 'CURE International', 'slug' => 'cure-international', 'description' => null, 'logo' => null, 'publish' => false],
            ['name' => 'NCPWD Baringo', 'slug' => 'ncpwd-baringo', 'description' => null, 'logo' => null, 'publish' => false],
        ];

        // Keep legacy slug alias for older seeds / story links
        $legacyUthabiti = Partner::withTrashed()->where('slug', 'uthabiti-africa')->first();
        if ($legacyUthabiti && ! Partner::withTrashed()->where('slug', 'uthabiti')->exists()) {
            $legacyUthabiti->update(['slug' => 'uthabiti']);
        }

        $partners = [];
        foreach ($definitions as $index => $definition) {
            $logoId = $definition['logo']
                ? $this->ensurePublicMedia($definition['logo'], $definition['name'].' logo', 'partners')?->id
                : null;

            // Prefer matching an existing partner by name so slug renames do not create duplicates.
            $partner = Partner::withTrashed()
                ->where(function ($query) use ($definition) {
                    $query->where('slug', $definition['slug'])
                        ->orWhereRaw('lower(name) = ?', [mb_strtolower($definition['name'])]);
                })
                ->first();

            $payload = [
                'name' => $definition['name'],
                'slug' => $definition['slug'],
                'description' => $definition['description'],
                'logo_id' => $logoId,
                'sort_order' => $index + 1,
                'status' => $definition['publish'] ? PublishStatus::Published : PublishStatus::Draft,
                'published_at' => $definition['publish'] ? now() : null,
                'verification_status' => $definition['publish']
                    ? VerificationStatus::Verified
                    : VerificationStatus::NeedsVerification,
            ];

            if ($partner) {
                if ($partner->trashed()) {
                    $partner->restore();
                }
                $partner->update($payload);
            } else {
                $partner = Partner::create($payload);
            }

            $partners[$definition['slug']] = $partner;
        }

        return $partners;
    }

    private function seedTeamMembers(): void
    {
        // Sourced from https://asnenafrica.org/index.html Meet Our Team
        $members = [
            [
                'name' => 'Eva Naputuni (OGW)',
                'slug' => 'eva-naputuni',
                'title_role' => 'Founder',
                'photo' => 'team/eva-naputuni.jpg',
                'sort_order' => 1,
            ],
            [
                'name' => 'Catherine Macharia',
                'slug' => 'catherine-macharia',
                'title_role' => 'C.E.O | Communications Director',
                'photo' => 'team/catherine-macharia.jpg',
                'sort_order' => 2,
            ],
            [
                'name' => 'Cheruiyot Elkanah',
                'slug' => 'cheruiyot-elkanah',
                'title_role' => 'Resource mobilization and Partnerships Lead',
                'photo' => 'team/cheruiyot-elkanah.jpg',
                'sort_order' => 3,
            ],
            [
                'name' => 'John Mark',
                'slug' => 'john-mark',
                'title_role' => 'Social Media Director',
                'photo' => 'team/john-mark.jpg',
                'sort_order' => 4,
            ],
            [
                'name' => 'Erick Omondi',
                'slug' => 'erick-omondi',
                'title_role' => 'Creative Designer',
                'photo' => 'team/erick-omondi.jpg',
                'sort_order' => 5,
            ],
        ];

        foreach ($members as $member) {
            $photoId = $this->ensurePublicMedia($member['photo'], $member['name'], 'team')?->id;

            TeamMember::updateOrCreate(
                ['slug' => $member['slug']],
                [
                    'name' => $member['name'],
                    'title_role' => $member['title_role'],
                    'photo_id' => $photoId,
                    'sort_order' => $member['sort_order'],
                    'is_board' => false,
                    'status' => PublishStatus::Published,
                    'published_at' => now(),
                    'verification_status' => VerificationStatus::Verified,
                ]
            );
        }
    }

    private function seedEvents(): void
    {
        // Sourced from https://asnenafrica.org/event.html (deduped identical cards)
        $events = [
            [
                'title' => 'Alternative and Augmentative Communication (AAC)',
                'slug' => 'alternative-and-augmentative-communication-aac',
                'type' => 'webinar',
                'summary' => 'The conference will look into the trends, practices, and models of inclusion practiced in Africa.',
                'body' => '<p>The conference will look into the trends, practices, and models of inclusion practiced in Africa.</p>',
                'venue' => null,
                'is_online' => true,
                'online_url' => 'https://youtu.be/Qm004Ob7XL4',
                'starts_at' => '2026-07-20 19:00:00',
                'ends_at' => '2026-07-20 20:30:00',
                'image' => 'events/aac-communication.jpg',
            ],
            [
                'title' => 'Vacations with Neurodivergent',
                'slug' => 'vacations-with-neurodivergent',
                'type' => 'webinar',
                'summary' => 'Online webinar on vacations with neurodivergent children and families.',
                'body' => '<p>Online webinar exploring vacations with neurodivergent children and families.</p>',
                'venue' => null,
                'is_online' => true,
                'online_url' => null,
                'starts_at' => '2024-10-29 19:00:00',
                'ends_at' => '2024-10-29 21:00:00',
                'image' => 'events/vacations-neurodivergent.jpg',
            ],
            [
                'title' => 'Evidence and the Nature of the Neurodivergent Brain',
                'slug' => 'evidence-and-the-nature-of-the-neurodivergent-brain',
                'type' => 'webinar',
                'summary' => 'Online webinar on evidence and the nature of the neurodivergent brain.',
                'body' => '<p>Online webinar on evidence and the nature of the neurodivergent brain.</p>',
                'venue' => null,
                'is_online' => true,
                'online_url' => null,
                'starts_at' => '2024-06-27 19:00:00',
                'ends_at' => '2024-06-27 21:00:00',
                'image' => 'events/evidence-neurodivergent-brain.jpg',
            ],
            [
                'title' => 'Celebrating Holidays | Vacation with Neurodivergent Children',
                'slug' => 'celebrating-holidays-vacation-with-neurodivergent-children',
                'type' => 'webinar',
                'summary' => 'Online webinar on celebrating holidays and vacationing with neurodivergent children.',
                'body' => '<p>Online webinar on celebrating holidays and vacationing with neurodivergent children.</p>',
                'venue' => null,
                'is_online' => true,
                'online_url' => null,
                'starts_at' => '2024-10-29 19:00:00',
                'ends_at' => '2024-10-29 21:00:00',
                'image' => 'events/celebrating-holidays.jpg',
            ],
            [
                'title' => 'Dynamic Differences',
                'slug' => 'dynamic-differences',
                'type' => 'webinar',
                'summary' => 'Online webinar on dynamic differences.',
                'body' => '<p>Online webinar on dynamic differences.</p>',
                'venue' => null,
                'is_online' => true,
                'online_url' => null,
                'starts_at' => '2024-02-28 19:00:00',
                'ends_at' => '2024-02-28 21:00:00',
                'image' => 'events/dynamic-differences.jpg',
            ],
            [
                'title' => 'Dyslexia, Dyscalculia, Dysgraphia & Dyspraxia',
                'slug' => 'dyslexia-dyscalculia-dysgraphia-dyspraxia',
                'type' => 'webinar',
                'summary' => 'Online webinar on dyslexia, dyscalculia, dysgraphia, and dyspraxia.',
                'body' => '<p>Online webinar on dyslexia, dyscalculia, dysgraphia, and dyspraxia.</p>',
                'venue' => null,
                'is_online' => true,
                'online_url' => null,
                'starts_at' => '2024-09-05 19:00:00',
                'ends_at' => '2024-09-05 21:00:00',
                'image' => 'events/dyslexia-dyscalculia.jpg',
            ],
            [
                'title' => 'Social Emotional Needs and Strengths of Neurodivergent Students',
                'slug' => 'social-emotional-needs-strengths-neurodivergent-students',
                'type' => 'webinar',
                'summary' => 'Online webinar on social-emotional needs and strengths of neurodivergent students.',
                'body' => '<p>Online webinar on social-emotional needs and strengths of neurodivergent students.</p>',
                'venue' => null,
                'is_online' => true,
                'online_url' => null,
                'starts_at' => '2024-05-30 19:00:00',
                'ends_at' => '2024-05-30 21:00:00',
                'image' => 'events/social-emotional-needs.jpg',
            ],
            [
                'title' => 'Introduction to Learning Differences',
                'slug' => 'introduction-to-learning-differences',
                'type' => 'webinar',
                'summary' => 'Online webinar introducing learning differences.',
                'body' => '<p>Online webinar introducing learning differences.</p>',
                'venue' => null,
                'is_online' => true,
                'online_url' => null,
                'starts_at' => '2024-07-30 19:00:00',
                'ends_at' => '2024-07-30 21:00:00',
                'image' => 'events/learning-differences.jpg',
            ],
            [
                'title' => 'Insurance for Neurodiverse Children',
                'slug' => 'insurance-for-neurodiverse-children',
                'type' => 'webinar',
                'summary' => 'Online webinar on insurance for neurodiverse children.',
                'body' => '<p>Online webinar on insurance for neurodiverse children.</p>',
                'venue' => null,
                'is_online' => true,
                'online_url' => null,
                'starts_at' => '2025-02-25 19:00:00',
                'ends_at' => '2025-02-25 21:00:00',
                'image' => 'events/insurance-neurodiverse.jpg',
            ],
            [
                'title' => 'Rare Disease & Disorders',
                'slug' => 'rare-disease-and-disorders',
                'type' => 'webinar',
                'summary' => 'Online webinar on rare diseases and disorders.',
                'body' => '<p>Online webinar on rare diseases and disorders.</p>',
                'venue' => null,
                'is_online' => true,
                'online_url' => null,
                'starts_at' => '2024-03-28 19:00:00',
                'ends_at' => '2024-03-28 21:00:00',
                'image' => 'events/rare-disease.jpg',
            ],
            [
                'title' => 'Improving Our Support System',
                'slug' => 'improving-our-support-system',
                'type' => 'webinar',
                'summary' => 'Online webinar on improving our support systems.',
                'body' => '<p>Online webinar on improving our support systems for children and families.</p>',
                'venue' => null,
                'is_online' => true,
                'online_url' => null,
                'starts_at' => '2025-03-25 19:00:00',
                'ends_at' => '2025-03-25 21:00:00',
                'image' => 'events/improving-support-system.jpg',
            ],
            [
                'title' => '2nd Ubuntu Conference',
                'slug' => '2nd-ubuntu-conference',
                'type' => 'conference',
                'summary' => 'Inclusion in Early Childhood Development',
                'body' => '<p>Inclusion in Early Childhood Development - the 2nd Ubuntu Conference convened partners and practitioners around African models of inclusive ECD.</p><p><a href="/storage/events/reports/ubuntu-conference-2-report.pdf">Download conference report (PDF)</a></p>',
                'venue' => 'Nairobi',
                'is_online' => false,
                'online_url' => null,
                'starts_at' => '2023-08-16 08:00:00',
                'ends_at' => '2023-08-16 10:00:00',
                'image' => 'events/ubuntu-conference-2.jpg',
            ],
            [
                'title' => '1st Ubuntu Conference',
                'slug' => '1st-ubuntu-conference',
                'type' => 'conference',
                'summary' => 'Reimagining Inclusive Education',
                'body' => '<p>Reimagining Inclusive Education - the inaugural Ubuntu Conference.</p><p><a href="/storage/events/reports/ubuntu-conference-1-report.pdf">Download conference report (PDF)</a></p>',
                'venue' => 'Nairobi',
                'is_online' => false,
                'online_url' => null,
                'starts_at' => '2022-11-01 08:00:00',
                'ends_at' => '2022-11-01 10:00:00',
                'image' => 'events/ubuntu-conference-1.jpg',
            ],
            [
                'title' => "Amplifying African Autistic Adults' Voices",
                'slug' => 'amplifying-african-autistic-adults-voices',
                'type' => 'webinar',
                'summary' => "Online webinar amplifying African autistic adults' voices.",
                'body' => '<p>Online webinar amplifying African autistic adults&rsquo; voices.</p>',
                'venue' => null,
                'is_online' => true,
                'online_url' => null,
                'starts_at' => '2024-04-25 19:00:00',
                'ends_at' => '2024-04-25 21:00:00',
                'image' => 'events/african-autistic-adult-voices.jpg',
            ],
            [
                'title' => 'Succession and Children with Disabilities',
                'slug' => 'succession-and-children-with-disabilities',
                'type' => 'webinar',
                'summary' => 'Online webinar on succession and children with disabilities.',
                'body' => '<p>Online webinar on succession and children with disabilities.</p>',
                'venue' => null,
                'is_online' => true,
                'online_url' => null,
                'starts_at' => '2023-07-26 18:00:00',
                'ends_at' => '2023-07-26 20:00:00',
                'image' => 'events/succession-children-disabilities.jpg',
            ],
        ];

        foreach ($events as $event) {
            $imageId = $this->ensurePublicMedia($event['image'], $event['title'], 'events')?->id;

            Event::updateOrCreate(
                ['slug' => $event['slug']],
                [
                    'title' => $event['title'],
                    'type' => $event['type'],
                    'summary' => $event['summary'],
                    'body' => $event['body'],
                    'venue' => $event['venue'],
                    'is_online' => $event['is_online'],
                    'online_url' => $event['online_url'],
                    'starts_at' => $event['starts_at'],
                    'ends_at' => $event['ends_at'],
                    'timezone' => 'Africa/Nairobi',
                    'featured_image_id' => $imageId,
                    'status' => PublishStatus::Published,
                    'published_at' => now(),
                    'verification_status' => VerificationStatus::Verified,
                ]
            );
        }
    }

    private function seedGalleries(): void
    {
        // Sourced from https://asnenafrica.org/blog.html - captions left empty for admin editing
        $albums = [
            [
                'title' => 'IDPWD 2026',
                'slug' => 'idpwd-2026',
                'description' => 'International Day of Persons with Disabilities 2026.',
                'gallery_date' => '2026-12-03',
                'folder' => 'galleries/idpwd-2026',
            ],
            [
                'title' => 'International Day of Persons with Disability 2025',
                'slug' => 'idpwd-2025',
                'description' => 'International Day of Persons with Disabilities 2025.',
                'gallery_date' => '2025-12-03',
                'folder' => 'galleries/idpwd-2025',
            ],
            [
                'title' => 'Baringo 2023',
                'slug' => 'baringo-2023',
                'description' => 'Community outreach and medical camp moments from Baringo County, 2023.',
                'location' => 'Baringo County, Kenya',
                'gallery_date' => '2023-12-06',
                'folder' => 'galleries/baringo-2023',
            ],
            [
                'title' => 'Community Moments',
                'slug' => 'community-moments',
                'description' => 'Additional ASNEN programme and community photographs. Add captions in the admin panel.',
                'gallery_date' => null,
                'folder' => 'galleries/community-moments',
            ],
            [
                'title' => '1st Ubuntu Conference 2022',
                'slug' => '1st-ubuntu-conference-2022',
                'description' => 'Moments from the inaugural Ubuntu Conference - Reimagining Inclusive Education.',
                'location' => 'Nairobi, Kenya',
                'gallery_date' => '2022-11-01',
                'folder' => 'galleries/1st-ubuntu-conference-2022',
            ],
        ];

        foreach ($albums as $album) {
            $gallery = Gallery::updateOrCreate(
                ['slug' => $album['slug']],
                [
                    'title' => $album['title'],
                    'description' => $album['description'],
                    'location' => $album['location'] ?? null,
                    'gallery_date' => $album['gallery_date'],
                    'status' => PublishStatus::Published,
                    'published_at' => now(),
                    'requires_safeguarding' => false,
                    'safeguarding_status' => SafeguardingStatus::NotRequired,
                ]
            );

            $dir = storage_path('app/public/'.$album['folder']);
            if (! File::isDirectory($dir)) {
                continue;
            }

            $files = collect(File::files($dir))
                ->filter(fn ($file) => in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp', 'gif'], true))
                ->sortBy(fn ($file) => $file->getFilename())
                ->values();

            $keptIds = [];
            foreach ($files as $index => $file) {
                $relative = $album['folder'].'/'.$file->getFilename();
                $media = $this->ensurePublicMedia(
                    $relative,
                    $album['title'].' - photo '.($index + 1),
                    'galleries'
                );

                if (! $media) {
                    continue;
                }

                $item = GalleryItem::updateOrCreate(
                    [
                        'gallery_id' => $gallery->id,
                        'media_asset_id' => $media->id,
                    ],
                    [
                        'caption' => null,
                        'sort_order' => $index + 1,
                    ]
                );
                $keptIds[] = $item->id;
            }

            if ($keptIds !== []) {
                GalleryItem::query()
                    ->where('gallery_id', $gallery->id)
                    ->whereNotIn('id', $keptIds)
                    ->delete();
            }
        }
    }


    private function ensurePublicMedia(string $relativePath, string $alt, string $folder): ?MediaAsset
    {
        $absolute = storage_path('app/public/'.$relativePath);
        if (! File::exists($absolute)) {
            return null;
        }

        $size = File::size($absolute);
        $mime = File::mimeType($absolute) ?: 'image/jpeg';
        $filename = basename($relativePath);

        return MediaAsset::updateOrCreate(
            ['path' => $relativePath, 'disk' => 'public'],
            [
                'filename' => $filename,
                'mime' => $mime,
                'size' => $size,
                'alt' => $alt,
                'folder' => $folder,
                'is_private' => false,
                'consent_status' => ConsentStatus::NotRequired,
                'credit' => 'Africa Special Needs Education Network (asnenafrica.org)',
            ]
        );
    }

    private function seedImpactMetrics(): array
    {
        $defs = [
            [
                'label' => 'Workshops',
                'value' => '150',
                'numeric_value' => 150,
                'public_label' => '150',
                'source_label' => 'asnenafrica.org',
            ],
            [
                'label' => 'Volunteers',
                'value' => '400',
                'numeric_value' => 400,
                'public_label' => '400',
                'source_label' => 'asnenafrica.org',
            ],
            [
                'label' => 'Conference',
                'value' => '2',
                'numeric_value' => 2,
                'public_label' => '2',
                'source_label' => 'asnenafrica.org',
            ],
            [
                'label' => 'Webinars',
                'value' => '15',
                'numeric_value' => 15,
                'public_label' => '15',
                'source_label' => 'asnenafrica.org',
            ],
            [
                'label' => 'Disability registrations / medical camp',
                'value' => '4',
                'numeric_value' => 4,
                'public_label' => '4',
                'source_label' => 'asnenafrica.org',
            ],
        ];

        $ids = [];
        foreach ($defs as $def) {
            $metric = ImpactMetric::updateOrCreate(
                ['label' => $def['label']],
                [
                    'value' => $def['value'],
                    'numeric_value' => $def['numeric_value'],
                    'public_label' => $def['public_label'],
                    'source_label' => $def['source_label'],
                    'verification_status' => VerificationStatus::Verified,
                    'status' => PublishStatus::Published,
                    'published_at' => now(),
                ]
            );
            $ids[] = $metric->id;
        }

        // Keep older draft/legacy labels from colliding with homepage counters.
        ImpactMetric::query()
            ->whereIn('label', [
                'Workshops delivered',
                'Volunteers engaged',
                'Conferences held',
                'Webinars (legacy count)',
                '2024 webinar participants',
            ])
            ->update([
                'status' => PublishStatus::Draft,
                'published_at' => null,
            ]);

        return $ids;
    }

    /** @return list<Webinar> */
    private function seedWebinars(): array
    {
        // Online webinars from https://asnenafrica.org/event.html (deduped)
        $definitions = [
            [
                'title' => 'Vacations with Neurodivergent',
                'slug' => 'vacations-with-neurodivergent',
                'summary' => 'Online webinar on vacations with neurodivergent children and families.',
                'held_at' => '2024-10-29 19:00:00',
                'image' => 'events/vacations-neurodivergent.jpg',
            ],
            [
                'title' => 'Evidence and the Nature of the Neurodivergent Brain',
                'slug' => 'evidence-and-the-nature-of-the-neurodivergent-brain',
                'summary' => 'Online webinar on evidence and the nature of the neurodivergent brain.',
                'held_at' => '2024-06-27 19:00:00',
                'image' => 'events/evidence-neurodivergent-brain.jpg',
            ],
            [
                'title' => 'Celebrating Holidays | Vacation with Neurodivergent Children',
                'slug' => 'celebrating-holidays-vacation-with-neurodivergent-children',
                'summary' => 'Online webinar on celebrating holidays and vacationing with neurodivergent children.',
                'held_at' => '2024-10-29 19:00:00',
                'image' => 'events/celebrating-holidays.jpg',
            ],
            [
                'title' => 'Dynamic Differences',
                'slug' => 'dynamic-differences',
                'summary' => 'Online webinar on dynamic differences.',
                'held_at' => '2024-02-28 19:00:00',
                'image' => 'events/dynamic-differences.jpg',
            ],
            [
                'title' => 'Dyslexia, Dyscalculia, Dysgraphia & Dyspraxia',
                'slug' => 'dyslexia-dyscalculia-dysgraphia-dyspraxia',
                'summary' => 'Online webinar on dyslexia, dyscalculia, dysgraphia, and dyspraxia.',
                'held_at' => '2024-09-05 19:00:00',
                'image' => 'events/dyslexia-dyscalculia.jpg',
            ],
            [
                'title' => 'Social Emotional Needs and Strengths of Neurodivergent Students',
                'slug' => 'social-emotional-needs-strengths-neurodivergent-students',
                'summary' => 'Online webinar on social-emotional needs and strengths of neurodivergent students.',
                'held_at' => '2024-05-30 19:00:00',
                'image' => 'events/social-emotional-needs.jpg',
            ],
            [
                'title' => 'Introduction to Learning Differences',
                'slug' => 'introduction-to-learning-differences',
                'summary' => 'Online webinar introducing learning differences.',
                'held_at' => '2024-07-30 19:00:00',
                'image' => 'events/learning-differences.jpg',
            ],
            [
                'title' => 'Insurance for Neurodiverse Children',
                'slug' => 'insurance-for-neurodiverse-children',
                'summary' => 'Online webinar on insurance for neurodiverse children.',
                'held_at' => '2025-02-25 19:00:00',
                'image' => 'events/insurance-neurodiverse.jpg',
            ],
            [
                'title' => 'Rare Disease & Disorders',
                'slug' => 'rare-disease-and-disorders',
                'summary' => 'Online webinar on rare diseases and disorders.',
                'held_at' => '2024-03-28 19:00:00',
                'image' => 'events/rare-disease.jpg',
            ],
            [
                'title' => 'Improving Our Support System',
                'slug' => 'improving-our-support-system',
                'summary' => 'Online webinar on improving our support systems.',
                'held_at' => '2025-03-25 19:00:00',
                'image' => 'events/improving-support-system.jpg',
            ],
            [
                'title' => "Amplifying African Autistic Adults' Voices",
                'slug' => 'amplifying-african-autistic-adults-voices',
                'summary' => "Online webinar amplifying African autistic adults' voices.",
                'held_at' => '2024-04-25 19:00:00',
                'image' => 'events/african-autistic-adult-voices.jpg',
            ],
            [
                'title' => 'Succession and Children with Disabilities',
                'slug' => 'succession-and-children-with-disabilities',
                'summary' => 'Online webinar on succession and children with disabilities.',
                'held_at' => '2023-07-26 18:00:00',
                'image' => 'events/succession-children-disabilities.jpg',
            ],
            [
                'title' => 'Alternative and Augmentative Communication (AAC)',
                'slug' => 'alternative-and-augmentative-communication-aac',
                'summary' => 'The conference will look into the trends, practices, and models of inclusion practiced in Africa.',
                'held_at' => '2026-07-20 19:00:00',
                'image' => 'events/aac-communication.jpg',
                'recording_url' => 'https://youtu.be/Qm004Ob7XL4',
            ],
        ];

        Webinar::where('slug', 'like', 'webinar-2024-%')->forceDelete();

        $webinars = [];
        foreach ($definitions as $definition) {
            $imageId = $this->ensurePublicMedia($definition['image'], $definition['title'], 'events')?->id;

            $webinars[] = Webinar::updateOrCreate(
                ['slug' => $definition['slug']],
                [
                    'title' => $definition['title'],
                    'summary' => $definition['summary'],
                    'body' => '<p>'.$definition['summary'].'</p>',
                    'held_at' => $definition['held_at'],
                    'recording_url' => $definition['recording_url'] ?? null,
                    'featured_image_id' => $imageId,
                    'status' => PublishStatus::Published,
                    'published_at' => now(),
                    'verification_status' => VerificationStatus::Verified,
                ]
            );
        }

        return $webinars;
    }

    /**
     * @param  array<string, Program>  $programs
     * @param  array<string, Partner>  $partners
     */
    private function seedKomolionStory(array $programs, array $partners): ImpactStory
    {
        $featuredImageId = $this->ensurePublicMedia(
            'galleries/baringo-2023/01.jpg',
            'Komolion 2023 disability assessment and medical camp, Baringo County',
            'impact'
        )?->id;

        $story = ImpactStory::updateOrCreate(
            ['slug' => 'komolion-2023-disability-assessment-medical-camp'],
            [
                'title' => 'Komolion 2023: Disability Assessment, NCPWD Registration & Orthopedic Medical Camp',
                'summary' => 'A coordinated outreach in Baringo County registering 54 persons with disabilities with NCPWD and identifying 12 candidates for corrective surgery-including three infants under age three.',
                'body' => <<<'HTML'
<p>On 6 December 2023, ASNEN and Acorn Special Tutorials- with support from Noogera Foundation and in collaboration with CURE International and NCPWD Baringo-held a disability assessment, NCPWD registration, and orthopedic medical camp at Komolion Primary School and Komolion Centre in Baringo County.</p>
<p>The initiative aimed to raise awareness, extend support, combat stigma, and improve pathways to inclusion for persons with disabilities in the community. Services included assessment, registration with the National Council for Persons with Disabilities (NCPWD), and orthopedic screening with referral pathways for corrective surgery where clinically indicated.</p>
<p>This case study presents aggregate, evidence-led outcomes. Individual medical details and identifying information about children are not published without documented safeguarding and consent review.</p>
HTML,
                'location' => 'Komolion Primary School & Komolion Centre, Baringo County, Kenya',
                'story_date' => '2023-12-06',
                'featured_image_id' => $featuredImageId,
                'status' => PublishStatus::Published,
                'published_at' => now(),
                'requires_safeguarding' => false,
                'safeguarding_status' => SafeguardingStatus::NotRequired,
                'verification_status' => VerificationStatus::Verified,
                'challenges' => 'Communication gaps during community mobilization affected reach to some households. Coordinating multiple partners across registration, assessment, and surgical referral required careful sequencing on the day.',
                'learnings' => 'Early engagement with local officials strengthens turnout and trust. Parent support networks and visible local representation help reduce stigma and sustain follow-up after camp activities.',
                'next_steps' => 'Continue advocacy in Baringo County, strengthen surgical-support partnerships with CURE International, establish parent support networks, and plan follow-up visits to track registration and referral outcomes.',
            ]
        );

        $outcomes = [
            ['label' => 'NCPWD registrations completed', 'value' => '54'],
            ['label' => 'Identified for corrective surgery (CURE International)', 'value' => '12'],
            ['label' => 'Infants under age three identified for surgery', 'value' => '3'],
            ['label' => 'School-going children identified for surgery', 'value' => '9'],
        ];

        StoryOutcome::where('impact_story_id', $story->id)->delete();
        foreach ($outcomes as $index => $outcome) {
            StoryOutcome::create([
                'impact_story_id' => $story->id,
                'label' => $outcome['label'],
                'value' => $outcome['value'],
                'sort_order' => $index + 1,
            ]);
        }

        $partnerSlugs = [
            'acorn-special-tutorials',
            'noogera-foundation',
            'cure-international',
            'ncpwd-baringo',
        ];
        $partnerIds = collect($partnerSlugs)
            ->map(fn (string $slug) => $partners[$slug]->id ?? null)
            ->filter()
            ->all();
        $story->partners()->sync($partnerIds);

        $story->programs()->sync([
            $programs['community-outreach-medical-camps']->id,
            $programs['early-identification-intervention']->id,
        ]);

        $baringoGallery = Gallery::query()->where('slug', 'baringo-2023')->first();
        if ($baringoGallery) {
            $story->update(['gallery_id' => $baringoGallery->id]);
        }

        return $story;
    }

    private function seedPublications(): void
    {
        $definitions = [
            [
                'title' => '2nd Ubuntu Conference Report',
                'slug' => 'ubuntu-conference-2-report',
                'category' => 'conference_report',
                'year' => 2023,
                'abstract' => 'Report from Inclusion in Early Childhood Development - the 2nd Ubuntu Conference in Nairobi.',
                'file' => 'events/reports/ubuntu-conference-2-report.pdf',
                'cover' => 'events/ubuntu-conference-2.jpg',
            ],
            [
                'title' => '1st Ubuntu Conference Report',
                'slug' => 'ubuntu-conference-1-report',
                'category' => 'conference_report',
                'year' => 2022,
                'abstract' => 'Report from Reimagining Inclusive Education - the inaugural Ubuntu Conference in Nairobi.',
                'file' => 'events/reports/ubuntu-conference-1-report.pdf',
                'cover' => 'events/ubuntu-conference-1.jpg',
            ],
            [
                'title' => 'Inclusive Classroom Starter Guide',
                'slug' => 'inclusive-classroom-starter-guide',
                'category' => 'toolkit',
                'year' => 2024,
                'abstract' => 'A practical starter guide for teachers welcoming neurodiversity, learning differences, and diverse support needs in mainstream classrooms.',
                'file' => null,
                'cover' => 'events/ubuntu-conference-2.jpg',
                'version' => '1.0',
            ],
            [
                'title' => 'Caregiver Support Toolkit',
                'slug' => 'caregiver-support-toolkit',
                'category' => 'toolkit',
                'year' => 2024,
                'abstract' => 'Training manual and facilitator’s guide for caregivers of children with disability - practical tools, conversation prompts, and session outlines for homes and community settings.',
                'file' => null,
                'cover' => 'resources/caregiver-support-toolkit.jpg',
                'version' => '1.0',
            ],
            [
                'title' => 'Early Identification Pathway Guide',
                'slug' => 'early-identification-pathway-guide',
                'category' => 'guide',
                'year' => 2023,
                'abstract' => 'A community-facing guide to noticing concerns early and connecting families to assessment, referral, and support.',
                'file' => null,
                'cover' => 'events/ubuntu-conference-1.jpg',
                'version' => '1.0',
            ],
            [
                'title' => 'SAACS ASNEN: Alternative and Augmentative Communication (AAC)',
                'slug' => 'saacs-asnen-aac',
                'category' => 'report',
                'year' => 2026,
                'abstract' => 'Presentation materials from the ASNEN webinar on Alternative and Augmentative Communication (AAC).',
                'file' => 'resources/saacs-asnen.pdf',
                'cover' => 'events/aac-communication.jpg',
            ],
        ];

        foreach ($definitions as $definition) {
            $fileId = ! empty($definition['file'])
                ? $this->ensurePublicMedia(
                    $definition['file'],
                    $definition['title'].' PDF',
                    'resources'
                )?->id
                : null;

            $coverId = ! empty($definition['cover'])
                ? $this->ensurePublicMedia(
                    $definition['cover'],
                    $definition['title'].' cover',
                    str_contains($definition['cover'], '/')
                        ? explode('/', $definition['cover'])[0]
                        : 'events'
                )?->id
                : null;

            Publication::updateOrCreate(
                ['slug' => $definition['slug']],
                [
                    'title' => $definition['title'],
                    'category' => $definition['category'],
                    'year' => $definition['year'],
                    'abstract' => $definition['abstract'],
                    'version' => $definition['version'] ?? null,
                    'file_id' => $fileId,
                    'cover_id' => $coverId,
                    'status' => PublishStatus::Published,
                    'published_at' => now(),
                    'verification_status' => VerificationStatus::Verified,
                ]
            );
        }
    }

    /**
     * @param  array<string, Program>  $programs
     */
    private function seedPages(array $metricIds, ImpactStory $komolion, array $programs): void
    {
        $this->seedHomePage($metricIds, $komolion, $programs);
        $this->seedAboutPages();
        $this->seedWhatWeDoPages($programs);
        $this->seedImpactPages($komolion);
        $this->seedGetInvolvedPages();
        $this->seedUtilityPages();
    }

    /**
     * @param  array<string, Program>  $programs
     */
    private function seedHomePage(array $metricIds, ImpactStory $komolion, array $programs): void
    {
        $page = Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'template' => 'home',
                'excerpt' => 'Africa Special Needs Education Network - Inclusion for all, in all.',
                'status' => PublishStatus::Published,
                'published_at' => now(),
                'verification_status' => VerificationStatus::Verified,
                'requires_safeguarding' => false,
                'safeguarding_status' => SafeguardingStatus::NotRequired,
            ]
        );

        PageBlock::where('page_id', $page->id)->forceDelete();

        $blocks = [
            [
                'type' => 'announcement',
                'content' => ['source' => 'announcement_model'],
            ],
            [
                'type' => 'hero',
                'content' => [
                    'brand' => 'Demystifying Disability',
                    'headline' => 'Inclusion for all, in all. No child left behind.',
                    'supporting_text' => 'ASNEN is a coalition of families, educators and advocates across Africa, building a model of inclusion rooted in Ubuntu, carried by the people who live it, not delivered to them.',
                    'primary_cta' => ['label' => 'Explore Our Programs', 'url' => '/what-we-do'],
                    'secondary_cta' => ['label' => 'See Our Impact', 'url' => '/impact'],
                ],
            ],
            [
                'type' => 'who_we_are',
                'content' => [
                    'heading' => 'Who We Are',
                    'body' => 'The Africa Special Needs Education Network (ASNEN) is a coalition advancing the inclusion of children, young adults, and persons with special needs-especially persons with disabilities-across education and lifespan issues. We develop knowledge, build capacity, and foster collaboration so that inclusion becomes embedded in African communities.',
                ],
            ],
            [
                'type' => 'statistics',
                'content' => [
                    'heading' => 'Impact at a Glance',
                    'metric_ids' => $metricIds,
                ],
            ],
            [
                'type' => 'program_grid',
                'content' => [
                    'heading' => 'Our Programs',
                    'program_slugs' => array_keys($programs),
                ],
            ],
            [
                'type' => 'impact_story',
                'content' => [
                    'heading' => 'Featured Impact Story',
                    'story_id' => $komolion->id,
                    'story_slug' => $komolion->slug,
                ],
            ],
            [
                'type' => 'featured_events',
                'content' => [
                    'heading' => 'Upcoming events',
                    'intro' => 'Conferences, webinars, and gatherings coming up across the ASNEN network.',
                    'limit' => 3,
                    'show_upcoming_only' => true,
                    'show_past_only' => false,
                    'fallback_to_past' => true,
                ],
            ],
            [
                'type' => 'team',
                'content' => [
                    'heading' => 'Leadership & Team',
                    'limit' => 5,
                ],
            ],
            [
                'type' => 'testimonials',
                'content' => [
                    'heading' => 'Voices from Our Community',
                    'display' => 'consented_only',
                ],
                'is_visible' => false,
            ],
            [
                'type' => 'partners',
                'content' => [
                    'heading' => 'Our Collaborators',
                    'display' => 'verified_only',
                ],
            ],
            [
                'type' => 'get_involved',
                'content' => [
                    'heading' => 'Get Involved',
                    'pathways' => [
                        ['label' => 'Become a Member', 'url' => '/get-involved/membership'],
                        ['label' => 'Volunteer', 'url' => '/get-involved/volunteer'],
                        ['label' => 'Partner With Us', 'url' => '/get-involved/partner'],
                        ['label' => 'Donate', 'url' => '/get-involved/donate'],
                    ],
                ],
            ],
            [
                'type' => 'newsletter',
                'content' => [
                    'heading' => 'Stay Connected',
                    'form_slug' => 'newsletter',
                ],
            ],
        ];

        foreach ($blocks as $index => $block) {
            PageBlock::create([
                'page_id' => $page->id,
                'type' => $block['type'],
                'sort_order' => $index + 1,
                'is_visible' => $block['is_visible'] ?? true,
                'content' => $block['content'],
            ]);
        }
    }

    private function upsertContentPage(string $slug, string $title, string $excerpt, string $body, string $template = 'default'): Page
    {
        $page = Page::updateOrCreate(
            ['slug' => $slug],
            [
                'title' => $title,
                'template' => $template,
                'excerpt' => $excerpt,
                'status' => PublishStatus::Published,
                'published_at' => now(),
                'verification_status' => VerificationStatus::Verified,
                'requires_safeguarding' => false,
                'safeguarding_status' => SafeguardingStatus::NotRequired,
            ]
        );

        // Keep the canonical slug even when the title changes (HasSlug would otherwise rewrite it).
        if ($page->slug !== $slug) {
            $page->slug = $slug;
            $page->saveQuietly();
        }

        PageBlock::updateOrCreate(
            ['page_id' => $page->id, 'type' => 'rich_text'],
            [
                'sort_order' => 1,
                'is_visible' => true,
                'content' => ['body' => $body],
            ]
        );

        return $page;
    }

    private function seedAboutPages(): void
    {
        $pages = [
            [
                'slug' => 'about-who-we-are',
                'title' => 'Who We Are',
                'excerpt' => 'A pan-African coalition for inclusive education and disability inclusion.',
                'body' => '<p>The Africa Special Needs Education Network (ASNEN) brings together educators, caregivers, advocates, researchers, and community partners committed to inclusion across the lifespan. We believe African communities hold the wisdom, creativity, and resolve to build support systems that honour every person\'s dignity.</p><p>ASNEN develops and advances homegrown models that provide knowledge, information, capacity building, advocacy, collaboration, and practical support-so that inclusion is not an exception, but an expectation.</p>',
            ],
            [
                'slug' => 'about-mission-vision-values',
                'title' => 'Vision, Mission & Values',
                'excerpt' => 'Our vision, mission, and UBUNTU-grounded values guide everything we do.',
                'body' => '<h2>Vision</h2><p>An Africa where inclusion is woven into the fabric of society, where every person’s potential is recognised, and no one is left behind.</p><p>ASNEN envisions an Africa in which inclusion is not an exception granted to some, but a condition of belonging shared by all. Guided by Ubuntu, we see a continent where compassion, reciprocity and dignity form the foundation of education and support, and where the shared experience and expertise of African families, educators and advocates come together in a model of inclusion that is truly our own.</p><h2>Mission</h2><p>To build a homegrown African model of inclusion, through knowledge, capacity building, advocacy and support, so that children, young people and adults with disabilities are included in education and in every stage of life.</p><p>ASNEN is a coalition of advocates, families, educators and allies committed to the full inclusion of children, young people and adults with disabilities and special needs. We create and share knowledge, build the capacity of those who teach and care, advocate for the realization of rights, and stand alongside families as they navigate the systems around them.</p><h2>Core Values</h2><p>Our values are drawn from Ubuntu, the understanding that our humanity is bound to one another. They are written as behaviours rather than aspirations, so that members, partners and funders may hold us to them.</p><h2>Philosophy</h2><p>Ubuntu “I am because we are”, is not a tagline at ASNEN. It is the reason the work takes the shape it does. It is why we convene rather than compete, why we build peer circles rather than waiting lists, why caregivers become facilitators, and why we hold that inclusion belongs to everyone rather than to specialists alone. A person is a person through other people. The child who has been hidden is one of us. The mother who has carried this alone is one of us. We are because they are.</p>',
            ],
            [
                'slug' => 'about-our-story',
                'title' => 'Our Story',
                'excerpt' => 'How ASNEN grew from community need into a network for inclusive education across Africa.',
                'body' => '<p>ASNEN emerged from a shared recognition that children and young adults with special needs-especially persons with disabilities-deserve education and community life marked by belonging, not exclusion. What began as collaborative action among educators, caregivers, and advocates has grown into a network connecting programs, outreach initiatives, webinars, and partnerships across Kenya and beyond.</p><p>Our story is still being written by the communities we serve-in classrooms, caregiver circles, medical camps, and policy forums where inclusion takes root.</p>',
            ],
            [
                'slug' => 'about-leadership',
                'title' => 'Leadership & Team',
                'excerpt' => 'Meet the people guiding ASNEN - founding leadership, programme roles, and the team carrying inclusion work forward.',
                'body' => '<p>ASNEN\'s public team directory introduces the people guiding and supporting the network. Names, titles, and portraits are kept accurate and up to date.</p>',
            ],
            [
                'slug' => 'about-governance',
                'title' => 'Governance',
                'excerpt' => 'Accountability structures that guide ASNEN\'s work with transparency and integrity.',
                'body' => '<p>ASNEN operates with governance practices that support ethical stewardship, safeguarding, financial accountability, and community accountability. Board and governance details are published here once verified by administrators.</p>',
            ],
            [
                'slug' => 'about-partners',
                'title' => 'Collaborators',
                'excerpt' => 'Organisations collaborating with ASNEN to advance inclusion across Africa.',
                'body' => '<p>ASNEN\'s impact is strengthened through collaboration with schools, NGOs, health institutions, and community organisations. Collaborator profiles are listed here once names, descriptions, logos, and URLs have been verified by administrators.</p>',
            ],
        ];

        foreach ($pages as $pageData) {
            $this->upsertContentPage(
                $pageData['slug'],
                $pageData['title'],
                $pageData['excerpt'],
                $pageData['body']
            );
        }
    }

    /**
     * @param  array<string, Program>  $programs
     */
    private function seedWhatWeDoPages(array $programs): void
    {
        $this->upsertContentPage(
            'what-we-do',
            'What We Do',
            'Programs and services advancing inclusive education, caregiver support, advocacy, and community outreach.',
            '<p>ASNEN delivers programs across inclusive education, caregiver training, early identification, advocacy, social inclusion, research and partnerships, and community outreach-including medical camps that extend support to underserved communities.</p>'
        );

        foreach ($programs as $program) {
            $this->upsertContentPage(
                'what-we-do-'.$program->slug,
                $program->title,
                $program->summary ?? '',
                $program->body ?? '',
                'program'
            );
        }
    }

    private function seedImpactPages(ImpactStory $komolion): void
    {
        $pages = [
            [
                'slug' => 'impact',
                'title' => 'Impact',
                'excerpt' => 'Evidence-led stories, verified figures, and learning from programmes across the ASNEN network.',
                'body' => '<p>ASNEN measures and shares impact with clear sources and as-of dates. Highlights from our 2024 annual report include a webinar series reaching more than 700 participants collectively, alongside community outreach such as the Komolion medical camp in Baringo County.</p>',
            ],
            [
                'slug' => 'impact-komolion',
                'title' => 'Komolion Story',
                'excerpt' => '2023 disability assessment, NCPWD registration, and orthopedic medical camp in Baringo County.',
                'body' => $komolion->body,
            ],
            [
                'slug' => 'impact-stories',
                'title' => 'Success Stories',
                'excerpt' => 'Impact stories from ASNEN programs and community initiatives.',
                'body' => '<p>Explore case studies and narratives that document ASNEN\'s work. Stories involving children or sensitive medical details require safeguarding review before publication.</p>',
            ],
            [
                'slug' => 'impact-reports',
                'title' => 'Impact Reports',
                'excerpt' => 'Download annual and conference reports documenting ASNEN programmes, learning, and verified progress.',
                'body' => '<p>These reports share practice and learning from ASNEN conferences and programmes. Download the PDFs below. Financial overviews appear only when verified figures are available.</p>',
            ],
            [
                'slug' => 'impact-regions',
                'title' => 'Impact by Region',
                'excerpt' => 'How ASNEN\'s work reaches communities across Kenya and the wider African continent.',
                'body' => '<p>Regional impact summaries will be published here as verified data becomes available through programs, outreach, and partnerships.</p>',
            ],
        ];

        foreach ($pages as $pageData) {
            $this->upsertContentPage(
                $pageData['slug'],
                $pageData['title'],
                $pageData['excerpt'],
                $pageData['body']
            );
        }
    }

    private function seedGetInvolvedPages(): void
    {
        $pages = [
            [
                'slug' => 'get-involved',
                'title' => 'Get Involved',
                'excerpt' => 'Join ASNEN as a member, volunteer, partner, or supporter.',
                'body' => '<p>There are many ways to support inclusive education and disability inclusion across Africa. Choose the pathway that fits your capacity and calling.</p>',
            ],
            [
                'slug' => 'get-involved-membership',
                'title' => 'Become a Member',
                'excerpt' => 'Join ASNEN\'s network of educators, caregivers, advocates, and organisations committed to inclusion for all, in all.',
                'body' => '<p>Membership connects you to resources, events, and a community carrying inclusive education forward. Choose a pathway below and apply - our team will follow up with next steps.</p>',
            ],
            [
                'slug' => 'get-involved-volunteer',
                'title' => 'Volunteer',
                'excerpt' => 'Offer your time and skills to advance inclusive education and disability inclusion across Africa.',
                'body' => '<p>Volunteers strengthen programmes, events, outreach, and communications. Share your skills and availability below - ASNEN will follow up when there is a suitable opportunity.</p>',
            ],
            [
                'slug' => 'get-involved-partner',
                'title' => 'Partner With Us',
                'excerpt' => 'Explore strategic collaboration with ASNEN on inclusive education, outreach, and community programmes.',
                'body' => '<p>ASNEN welcomes partnerships with organisations that share our commitment to dignity, inclusion, and community-led change. Submit a partnership inquiry to begin the conversation.</p>',
            ],
            [
                'slug' => 'get-involved-donate',
                'title' => 'Donate / Support a Program',
                'excerpt' => 'Support ASNEN programmes advancing inclusive education, caregiver training, and community outreach.',
                'body' => '<p>Your support helps extend inclusive education, caregiver training, community outreach, and medical camps to more communities. Use the inquiry form and ASNEN will follow up with secure next steps.</p>',
            ],
            [
                'slug' => 'gallery',
                'title' => 'Gallery',
                'excerpt' => 'Photographs from ASNEN programs and events-published with consent and descriptive metadata.',
                'body' => '<p>Gallery albums will appear here as media assets are uploaded and approved for public display.</p>',
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact',
                'excerpt' => 'Reach the Africa Special Needs Education Network.',
                'body' => '<p>We welcome inquiries about programs, partnerships, events, and membership. Contact details are managed in site settings and verified before publication.</p>',
            ],
        ];

        foreach ($pages as $pageData) {
            $this->upsertContentPage(
                $pageData['slug'],
                $pageData['title'],
                $pageData['excerpt'],
                $pageData['body']
            );
        }
    }

    private function seedUtilityPages(): void
    {
        $pages = [
            [
                'slug' => 'accessibility',
                'title' => 'Accessibility Statement',
                'excerpt' => 'Our commitment to an accessible digital experience for all visitors.',
                'body' => '<p>ASNEN is committed to an inclusive web experience that conforms to <strong>WCAG 2.2 Level AA</strong>. Accessibility is part of our Ubuntu values: dignity, belonging, and honest accounting.</p><h2>What we provide</h2><ul><li>Keyboard access to all primary journeys</li><li>Visible focus and skip links</li><li>A site-wide accessibility preferences panel (button bottom-left, or Alt+0)</li><li>Captions/transcript fields for webinars and video where available</li><li>Safeguarding and consent controls for stories and images involving children and persons with disabilities</li></ul><h2>Feedback</h2><p>If you find a barrier, please contact us at <a href="mailto:info@asnenafrica.org">info@asnenafrica.org</a> or use our contact form. Describe the page, the barrier, and your assistive technology if relevant. We will respond and work to fix issues.</p><h2>Standards we aim for</h2><p>Semantic HTML, sufficient colour contrast, resizable text to 200%+, reduced-motion support, accessible forms with error summaries, and plain-language options such as easy-read summaries on key pages.</p>',
            ],
            [
                'slug' => 'privacy',
                'title' => 'Privacy Policy',
                'excerpt' => 'How ASNEN collects, uses, and protects personal information.',
                'body' => '<p>This policy describes how the Africa Special Needs Education Network handles personal data submitted through forms, newsletters, and event registrations.</p>',
            ],
            [
                'slug' => 'terms',
                'title' => 'Terms of Use',
                'excerpt' => 'Terms governing use of the ASNEN website and digital services.',
                'body' => '<p>By using this website, you agree to these terms of use. Content is provided for informational purposes and may be updated without notice.</p>',
            ],
            [
                'slug' => 'cookies',
                'title' => 'Cookie Policy',
                'excerpt' => 'Information about cookies and consent preferences on asnenafrica.org.',
                'body' => '<p>We use cookies to improve site functionality and, with consent, analytics. You may manage preferences through our cookie consent tool.</p>',
            ],
            [
                'slug' => 'faqs',
                'title' => 'Frequently Asked Questions',
                'excerpt' => 'Common questions about ASNEN, membership, volunteering, and inclusion.',
                'body' => '<p>Find answers to frequently asked questions below.</p>',
            ],
            [
                'slug' => 'safeguarding',
                'title' => 'Safeguarding & Child Protection',
                'excerpt' => 'ASNEN\'s commitment to safeguarding children and persons with disabilities.',
                'body' => '<p>ASNEN maintains safeguarding controls for stories, photographs, and testimonials involving children and persons with disabilities. Content requiring safeguarding review remains unpublished until approved by a designated reviewer.</p>',
            ],
        ];

        foreach ($pages as $pageData) {
            $this->upsertContentPage(
                $pageData['slug'],
                $pageData['title'],
                $pageData['excerpt'],
                $pageData['body']
            );
        }
    }

    private function seedFaqs(): void
    {
        $faqs = [
            [
                'question' => 'What is the Africa Special Needs Education Network (ASNEN)?',
                'answer' => 'ASNEN is a pan-African coalition championing the inclusion of children, young adults, and persons with special needs-especially persons with disabilities-through inclusive education, caregiver training, advocacy, and community outreach.',
                'category' => 'general',
            ],
            [
                'question' => 'What does "Inclusion for all, in all" mean?',
                'answer' => 'It is ASNEN\'s primary message: inclusion should be embedded in every setting-schools, families, communities, and policy-so that no child is left behind and every person is treated with dignity.',
                'category' => 'general',
            ],
            [
                'question' => 'How can I become a member of ASNEN?',
                'answer' => 'Visit the Get Involved section and complete the membership application form. Our team will review your application and follow up with next steps.',
                'category' => 'membership',
            ],
            [
                'question' => 'Can I volunteer with ASNEN?',
                'answer' => 'Yes. ASNEN welcomes volunteers with relevant skills and availability. Submit a volunteer application describing your interests and we will match you to suitable opportunities where possible.',
                'category' => 'volunteering',
            ],
            [
                'question' => 'How does ASNEN approach stories about children and disability?',
                'answer' => 'We use person-centred, dignity-affirming language and maintain safeguarding controls. Stories involving children or sensitive medical details require consent and reviewer approval before publication.',
                'category' => 'safeguarding',
            ],
            [
                'question' => 'Where does ASNEN operate?',
                'answer' => 'ASNEN is based in Nairobi, Kenya, and collaborates with partners across Kenya and the wider African continent through programs, webinars, and community outreach.',
                'category' => 'general',
            ],
            [
                'question' => 'How can my organisation partner with ASNEN?',
                'answer' => 'Organisations aligned with ASNEN\'s mission may submit a partnership inquiry through the website. We review each inquiry to explore collaboration on programs, events, research, or outreach.',
                'category' => 'partnerships',
            ],
            [
                'question' => 'Are ASNEN\'s impact statistics verified?',
                'answer' => 'Yes. Public impact figures include source labels and as-of dates so readers can see where the numbers come from.',
                'category' => 'impact',
            ],
        ];

        foreach ($faqs as $index => $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                [
                    'answer' => $faq['answer'],
                    'category' => $faq['category'],
                    'sort_order' => $index + 1,
                    'status' => PublishStatus::Published,
                    'published_at' => now(),
                ]
            );
        }
    }

    private function seedFormDefinitions(): void
    {
        $forms = [
            [
                'name' => 'Contact',
                'slug' => 'contact',
                'type' => 'contact',
                'fields' => [
                    ['name' => 'name', 'type' => 'text', 'label' => 'Full Name', 'required' => true],
                    ['name' => 'email', 'type' => 'email', 'label' => 'Email Address', 'required' => true],
                    ['name' => 'phone', 'type' => 'tel', 'label' => 'Phone Number', 'required' => false],
                    ['name' => 'subject', 'type' => 'text', 'label' => 'Subject', 'required' => true],
                    ['name' => 'message', 'type' => 'textarea', 'label' => 'Message', 'required' => true],
                ],
                'success_message' => 'Thank you for contacting ASNEN. We will respond as soon as possible.',
                'notify_emails' => ['info@asnenafrica.org'],
            ],
            [
                'name' => 'Membership Application',
                'slug' => 'membership',
                'type' => 'membership',
                'fields' => [
                    ['name' => 'name', 'type' => 'text', 'label' => 'Full Name', 'required' => true],
                    ['name' => 'email', 'type' => 'email', 'label' => 'Email Address', 'required' => true],
                    ['name' => 'phone', 'type' => 'tel', 'label' => 'Phone Number', 'required' => true],
                    ['name' => 'membership_type', 'type' => 'select', 'label' => 'Membership Type', 'required' => true, 'options' => ['individual', 'organizational']],
                    ['name' => 'motivation', 'type' => 'textarea', 'label' => 'Why do you want to join ASNEN?', 'required' => true],
                ],
                'success_message' => 'Your membership application has been received. We will be in touch shortly.',
                'notify_emails' => ['info@asnenafrica.org'],
            ],
            [
                'name' => 'Volunteer Application',
                'slug' => 'volunteer',
                'type' => 'volunteer',
                'fields' => [
                    ['name' => 'name', 'type' => 'text', 'label' => 'Full Name', 'required' => true],
                    ['name' => 'email', 'type' => 'email', 'label' => 'Email Address', 'required' => true],
                    ['name' => 'phone', 'type' => 'tel', 'label' => 'Phone Number', 'required' => true],
                    ['name' => 'skills', 'type' => 'textarea', 'label' => 'Skills and Experience', 'required' => true],
                    ['name' => 'availability', 'type' => 'text', 'label' => 'Availability', 'required' => true],
                ],
                'success_message' => 'Thank you for offering to volunteer with ASNEN.',
                'notify_emails' => ['info@asnenafrica.org'],
            ],
            [
                'name' => 'Partnership Inquiry',
                'slug' => 'partner',
                'type' => 'partnership',
                'fields' => [
                    ['name' => 'organisation', 'type' => 'text', 'label' => 'Organisation Name', 'required' => true],
                    ['name' => 'contact_name', 'type' => 'text', 'label' => 'Contact Person', 'required' => true],
                    ['name' => 'email', 'type' => 'email', 'label' => 'Email Address', 'required' => true],
                    ['name' => 'phone', 'type' => 'tel', 'label' => 'Phone Number', 'required' => false],
                    ['name' => 'proposal', 'type' => 'textarea', 'label' => 'Partnership Proposal', 'required' => true],
                ],
                'success_message' => 'Your partnership inquiry has been received.',
                'notify_emails' => ['info@asnenafrica.org'],
            ],
            [
                'name' => 'Donate / Program Support',
                'slug' => 'donate',
                'type' => 'donation',
                'fields' => [
                    ['name' => 'name', 'type' => 'text', 'label' => 'Full Name', 'required' => true],
                    ['name' => 'email', 'type' => 'email', 'label' => 'Email Address', 'required' => true],
                    ['name' => 'program_interest', 'type' => 'select', 'label' => 'Program to Support', 'required' => false, 'options' => ['general', 'inclusive-education', 'caregiver-training', 'community-outreach-medical-camps']],
                    ['name' => 'message', 'type' => 'textarea', 'label' => 'Message', 'required' => false],
                ],
                'success_message' => 'Thank you for your interest in supporting ASNEN.',
                'notify_emails' => ['info@asnenafrica.org'],
            ],
            [
                'name' => 'Newsletter',
                'slug' => 'newsletter',
                'type' => 'newsletter',
                'fields' => [
                    ['name' => 'name', 'type' => 'text', 'label' => 'Name (optional)', 'required' => false],
                    ['name' => 'email', 'type' => 'email', 'label' => 'Email Address', 'required' => true],
                    ['name' => 'consent', 'type' => 'checkbox', 'label' => 'I consent to receive ASNEN updates', 'required' => true],
                ],
                'success_message' => 'You have been subscribed to ASNEN updates.',
                'notify_emails' => ['info@asnenafrica.org'],
            ],
            [
                'name' => 'Toolkit Request',
                'slug' => 'toolkit-request',
                'type' => 'toolkit_request',
                'fields' => [
                    ['name' => 'name', 'type' => 'text', 'label' => 'Full Name', 'required' => true],
                    ['name' => 'email', 'type' => 'email', 'label' => 'Email Address', 'required' => true],
                    ['name' => 'phone', 'type' => 'tel', 'label' => 'Phone Number', 'required' => false],
                    ['name' => 'organisation', 'type' => 'text', 'label' => 'Organisation / school', 'required' => false],
                    ['name' => 'role', 'type' => 'select', 'label' => 'Your role', 'required' => true, 'options' => ['caregiver', 'teacher', 'facilitator', 'organisation', 'health_worker', 'other']],
                    ['name' => 'location', 'type' => 'text', 'label' => 'Location', 'required' => false],
                    ['name' => 'quantity', 'type' => 'number', 'label' => 'Copies needed', 'required' => false],
                    ['name' => 'message', 'type' => 'textarea', 'label' => 'How you plan to use the toolkit', 'required' => false],
                    ['name' => 'publication_title', 'type' => 'text', 'label' => 'Toolkit', 'required' => true],
                    ['name' => 'publication_slug', 'type' => 'text', 'label' => 'Toolkit slug', 'required' => true],
                ],
                'success_message' => 'Thank you. Your toolkit request has been received. ASNEN will follow up by email with next steps.',
                'notify_emails' => ['info@asnenafrica.org'],
            ],
        ];

        foreach ($forms as $form) {
            FormDefinition::updateOrCreate(
                ['slug' => $form['slug']],
                [
                    'name' => $form['name'],
                    'type' => $form['type'],
                    'fields' => $form['fields'],
                    'success_message' => $form['success_message'],
                    'notify_emails' => $form['notify_emails'],
                    'is_active' => true,
                ]
            );
        }
    }

    private function seedDonationCampaign(): void
    {
        DonationCampaign::updateOrCreate(
            ['slug' => 'support-a-program'],
            [
                'title' => 'Support a Program',
                'summary' => 'Direct your support to ASNEN programs advancing inclusive education, caregiver training, and community outreach.',
                'body' => '<p>ASNEN welcomes support for programs that extend inclusion to more communities. Payment processing will be enabled when a provider is configured. Until then, use the inquiry form to express your interest.</p>',
                'goal_amount' => null,
                'currency' => 'KES',
                'status' => PublishStatus::Published,
                'published_at' => now(),
            ]
        );
    }

    private function seedRedirects(): void
    {
        $redirects = [
            ['/index.html', '/'],
            ['/service.html', '/what-we-do'],
            ['/about.html', '/about'],
            ['/contact.html', '/contact'],
            ['/gallery.html', '/resources/gallery'],
            ['/gallery', '/resources/gallery'],
        ];

        foreach ($redirects as [$from, $to]) {
            Redirect::updateOrCreate(
                ['from_path' => $from],
                [
                    'to_path' => $to,
                    'status_code' => 301,
                    'is_active' => true,
                ]
            );
        }
    }

    private function seedMembershipPlans(): void
    {
        MembershipPlan::updateOrCreate(
            ['slug' => 'individual'],
            [
                'name' => 'Individual',
                'summary' => 'For educators, caregivers, advocates, and professionals committed to inclusive education.',
                'benefits' => [
                    'Access to ASNEN webinars and learning resources',
                    'Network with inclusion advocates across Africa',
                    'Updates on programs, events, and impact',
                ],
                'eligibility' => 'Open to individuals supporting inclusive education and disability inclusion.',
                'sort_order' => 1,
                'status' => PublishStatus::Published,
                'published_at' => now(),
            ]
        );

        MembershipPlan::updateOrCreate(
            ['slug' => 'organizational'],
            [
                'name' => 'Organisational',
                'summary' => 'For schools, NGOs, institutions, and organisations partnering in ASNEN\'s mission.',
                'benefits' => [
                    'Organisational visibility in ASNEN partner listings',
                    'Collaboration opportunities on programmes and events',
                    'Priority access to training and resource sharing',
                ],
                'eligibility' => 'Registered organisations aligned with ASNEN\'s mission and values.',
                'sort_order' => 2,
                'status' => PublishStatus::Published,
                'published_at' => now(),
            ]
        );
    }

    private function seedAnnouncement(): void
    {
        Announcement::updateOrCreate(
            ['message' => 'Welcome to the new ASNEN website - Inclusion for all, in all.'],
            [
                'link_url' => '/about/who-we-are',
                'link_label' => 'Learn about ASNEN',
                'is_active' => true,
                'starts_at' => now(),
            ]
        );
    }

    private function seedRegions(): void
    {
        $boundaries = require database_path('data/kenya-reach-boundaries.php');

        $definitions = [
            [
                'name' => 'Komolion, Baringo County',
                'slug' => 'komolion-baringo',
                'description' => 'Home of the 2023 disability assessment, NCPWD registration, and orthopedic medical camp with ASNEN partners.',
                'latitude' => 0.4667,
                'longitude' => 35.9667,
                'reach_radius_km' => 12,
                'map_color' => '#8CC63F',
                'country' => 'Kenya',
                'impact_label' => 'Medical camp · 2023',
                'link_url' => '/impact/komolion',
                'link_label' => 'Read Komolion story',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Nairobi',
                'slug' => 'nairobi',
                'description' => 'ASNEN\'s base for programmes, Ubuntu conferences, webinars, and network coordination.',
                'latitude' => -1.286389,
                'longitude' => 36.817223,
                'boundary_geojson' => $boundaries['nairobi'],
                'map_color' => '#0C77BC',
                'country' => 'Kenya',
                'impact_label' => 'Programmes & conferences',
                'link_url' => '/what-we-do',
                'link_label' => 'Explore programmes',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Baringo County',
                'slug' => 'baringo-county',
                'description' => 'Community outreach, caregiver engagement, and disability registration pathways across Baringo.',
                'latitude' => 0.6700,
                'longitude' => 35.9400,
                'boundary_geojson' => $boundaries['baringo-county'],
                'map_color' => '#0C77BC',
                'country' => 'Kenya',
                'impact_label' => 'Community outreach',
                'link_url' => '/what-we-do/community-outreach-medical-camps',
                'link_label' => 'Outreach programme',
                'is_featured' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'Kiambu County',
                'slug' => 'kiambu-county',
                'description' => 'School and caregiver collaboration supporting inclusive education practice near Nairobi.',
                'latitude' => -1.1714,
                'longitude' => 36.8356,
                'boundary_geojson' => $boundaries['kiambu-county'],
                'map_color' => '#5BA3D0',
                'country' => 'Kenya',
                'impact_label' => 'Inclusive education',
                'link_url' => '/what-we-do/inclusive-education',
                'link_label' => 'Inclusive education',
                'is_featured' => false,
                'sort_order' => 4,
            ],
            [
                'name' => 'Nakuru County',
                'slug' => 'nakuru-county',
                'description' => 'Regional learning and partnership activity connecting educators and community advocates.',
                'latitude' => -0.3031,
                'longitude' => 36.0800,
                'boundary_geojson' => $boundaries['nakuru-county'],
                'map_color' => '#3D8FBF',
                'country' => 'Kenya',
                'impact_label' => 'Network & learning',
                'link_url' => '/events-learning',
                'link_label' => 'Events & learning',
                'is_featured' => false,
                'sort_order' => 5,
            ],
        ];

        foreach ($definitions as $definition) {
            Region::updateOrCreate(
                ['slug' => $definition['slug']],
                [
                    ...$definition,
                    'status' => PublishStatus::Published,
                    'published_at' => now(),
                ]
            );
        }
    }

    private function seedArticles(): void
    {
        $definitions = [
            [
                'title' => 'Inclusion is an expectation, not an exception',
                'slug' => 'inclusion-is-an-expectation',
                'excerpt' => 'Why ASNEN frames belonging as everyday practice in classrooms, homes, and community life.',
                'category' => 'insight',
                'reading_time_minutes' => 4,
                'image' => 'events/ubuntu-conference-2.jpg',
                'body' => '<p>Across ASNEN\'s work, one conviction keeps returning: inclusion is not a special event. It is the expectation that every child and young adult belongs in learning and community life.</p><p>That conviction shapes how we train teachers, support caregivers, and tell stories. It also shapes how we partner - with schools, health institutions, and community organisations who share the same standard of dignity.</p><p>When we say <em>Inclusion for all, in all</em>, we mean practice you can see: classrooms that welcome neurodiversity, medical camps that open pathways to registration, and webinars that move knowledge into homes.</p>',
            ],
            [
                'title' => 'What Komolion taught the network',
                'slug' => 'what-komolion-taught-the-network',
                'excerpt' => 'Lessons from the 2023 disability assessment and medical camp in Baringo County.',
                'category' => 'field-note',
                'reading_time_minutes' => 5,
                'image' => 'galleries/baringo-2023/01.jpg',
                'body' => '<p>The Komolion outreach showed what becomes possible when education, health, and community partners pull together. Families came for assessment and registration. Children were seen with care. Pathways to surgery and ongoing support became clearer.</p><p>The work also reminded us that impact is local before it is large. One school compound. One day of coordinated service. Many relationships that continue after the camp ends.</p><p><a href="/impact/komolion">Read the full Komolion case study</a> for outcomes, partners, and next steps.</p>',
            ],
            [
                'title' => 'Learning that travels: webinars and toolkits',
                'slug' => 'learning-that-travels-webinars-and-toolkits',
                'excerpt' => 'How ASNEN turns live sessions into materials educators and caregivers can reuse.',
                'category' => 'learning',
                'reading_time_minutes' => 3,
                'image' => 'events/improving-support-system.jpg',
                'body' => '<p>ASNEN webinars are designed to keep teaching after the screen goes dark. Recordings live in the webinar library. Practical guides live in toolkits. Together they help knowledge travel into classrooms and caregiver circles.</p><p>If you facilitate workshops or support families, start with the <a href="/resources/webinars">webinar library</a> and pair a session with a relevant <a href="/resources/toolkits">toolkit</a>.</p>',
            ],
            [
                'title' => 'Ubuntu Conference: gathering around African models',
                'slug' => 'ubuntu-conference-gathering-around-african-models',
                'excerpt' => 'A look at ASNEN\'s flagship conference series and why Ubuntu remains the throughline.',
                'category' => 'events',
                'reading_time_minutes' => 4,
                'image' => 'events/ubuntu-conference-1.jpg',
                'body' => '<p>The Ubuntu Conference is ASNEN\'s flagship gathering for inclusive education. Each edition convenes educators, caregivers, advocates, and partners around African, homegrown models of practice.</p><p>Ubuntu - <em>I am because we are</em> - is not a slogan for the stage. It is the design principle for how we learn together and how we hold one another accountable after the hall empties.</p><p>Explore the <a href="/events-learning/ubuntu-conference">Ubuntu Conference page</a> and related reports in publications.</p>',
            ],
        ];

        foreach ($definitions as $index => $definition) {
            $imageId = $this->ensurePublicMedia(
                $definition['image'],
                $definition['title'],
                'news'
            )?->id;

            Article::updateOrCreate(
                ['slug' => $definition['slug']],
                [
                    'title' => $definition['title'],
                    'excerpt' => $definition['excerpt'],
                    'body' => $definition['body'],
                    'category' => $definition['category'],
                    'reading_time_minutes' => $definition['reading_time_minutes'],
                    'featured_image_id' => $imageId,
                    'status' => PublishStatus::Published,
                    'published_at' => now()->subDays($index * 7),
                ]
            );
        }
    }
}
