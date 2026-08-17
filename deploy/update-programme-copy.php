<?php

/**
 * Idempotent update of What We Do programme copy + SEO meta.
 * Safe to re-run on deploy.
 */

use App\Enums\PublishStatus;
use App\Enums\VerificationStatus;
use App\Models\Page;
use App\Models\Program;
use App\Models\SeoMeta;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$hubExcerpt = 'ASNEN advances inclusion through seven interconnected programme areas, moving knowledge, capacity and care into homes, classrooms and communities.';

$programmes = [
    [
        'title' => 'Inclusive Education',
        'slug' => 'inclusive-education',
        'summary' => 'ASNEN supports inclusive education through teacher training, classroom practice, and collaboration with schools and policymakers. We champion approaches that respect neurodiversity, learning differences, and diverse support needs, ensuring no child is left behind.',
        'body' => '<p>ASNEN supports inclusive education through teacher training, classroom practice, and collaboration with schools and policymakers. We champion approaches that respect neurodiversity, learning differences, and diverse support needs, ensuring no child is left behind.</p>',
        'sort_order' => 1,
        'seo_title' => 'Inclusive Education Programme',
        'seo_description' => 'ASNEN inclusive education in Africa: teacher training, classroom practice, school collaboration, and family partnership so no child is left behind.',
        'canonical_path' => '/what-we-do/inclusive-education',
    ],
    [
        'title' => 'Caregiver Training',
        'slug' => 'caregiver-training',
        'summary' => 'Our caregiver training equips parents, guardians, and professional caregivers with evidence-informed strategies, community connections, and ongoing support frameworks grounded in compassion and reciprocity.',
        'body' => '<p>Our caregiver training equips parents, guardians, and professional caregivers with evidence-informed strategies, community connections, and ongoing support frameworks grounded in compassion and reciprocity.</p><p>ASNEN launched the caregivers manual with Beyond Zero. The Caregiver Support Toolkit is a training manual and facilitator guide for families of children with disability, with practical tools for homes and community settings.</p>',
        'sort_order' => 2,
        'seo_title' => 'Caregiver Training Programme',
        'seo_description' => 'ASNEN caregiver training for parents and guardians: evidence-informed skills, peer support, and ongoing frameworks rooted in compassion and reciprocity.',
        'canonical_path' => '/what-we-do/caregiver-training',
    ],
    [
        'title' => 'Early Identification & Intervention',
        'slug' => 'early-identification-intervention',
        'summary' => 'ASNEN works with communities, schools, and health partners to improve pathways to assessment, referral, and early support, reducing delays that limit opportunity and inclusion.',
        'body' => '<p>ASNEN works with communities, schools, and health partners to improve pathways to assessment, referral, and early support, reducing delays that limit opportunity and inclusion.</p>',
        'sort_order' => 3,
        'seo_title' => 'Early Identification & Intervention',
        'seo_description' => 'ASNEN early identification and intervention: community pathways, school readiness, and health partnerships that shorten the wait for assessment and support.',
        'canonical_path' => '/what-we-do/early-identification-intervention',
    ],
    [
        'title' => 'Disability Awareness & Advocacy',
        'slug' => 'disability-awareness-advocacy',
        'summary' => 'Through workshops, campaigns, and coalition building, ASNEN amplifies the principle that nothing about us without us, centering lived experience in advocacy for inclusive policy and practice.',
        'body' => '<p>Through workshops, campaigns, and coalition building, ASNEN amplifies the principle that nothing about us without us, centering lived experience in advocacy for inclusive policy and practice.</p>',
        'sort_order' => 4,
        'seo_title' => 'Disability Awareness & Advocacy',
        'seo_description' => 'ASNEN disability awareness and advocacy: workshops, coalition building, and lived experience at the centre of inclusive policy and practice.',
        'canonical_path' => '/what-we-do/disability-awareness-advocacy',
    ],
    [
        'title' => 'Social Inclusion',
        'slug' => 'social-inclusion',
        'summary' => 'Social inclusion initiatives create spaces where children and young adults with disabilities participate fully in community life, building friendships, confidence, and mutual understanding rooted in Ubuntu.',
        'body' => '<p>Social inclusion initiatives create spaces where children and young adults with disabilities participate fully in community life, building friendships, confidence, and mutual understanding rooted in Ubuntu.</p>',
        'sort_order' => 5,
        'seo_title' => 'Social Inclusion Programme',
        'seo_description' => 'ASNEN social inclusion: community activities, peer connection, and confidence building for children and young adults with disabilities, rooted in Ubuntu.',
        'canonical_path' => '/what-we-do/social-inclusion',
    ],
    [
        'title' => 'Research, Policy & Partnerships',
        'slug' => 'research-policy-partnerships',
        'summary' => 'ASNEN collaborates with researchers, institutions, and networks to generate context-relevant knowledge, inform policy, and build sustainable partnerships for inclusion at scale.',
        'body' => '<p>ASNEN collaborates with researchers, institutions, and networks to generate context-relevant knowledge, inform policy, and build sustainable partnerships for inclusion at scale.</p>',
        'sort_order' => 6,
        'seo_title' => 'Research, Policy & Partnerships',
        'seo_description' => 'ASNEN research, policy and partnerships: African classroom evidence, policy dialogue, and strategic collaborations that advance inclusion at scale.',
        'canonical_path' => '/what-we-do/research-policy-partnerships',
    ],
    [
        'title' => 'Community Outreach & Medical Camps',
        'slug' => 'community-outreach-medical-camps',
        'summary' => 'Community outreach and medical camps, such as the Komolion initiative in Baringo County, extend pathways to registration, assessment, and surgical referral while combating stigma and raising local awareness.',
        'body' => '<p>Community outreach and medical camps, such as the Komolion initiative in Baringo County, extend pathways to registration, assessment, and surgical referral while combating stigma and raising local awareness.</p>',
        'sort_order' => 7,
        'seo_title' => 'Community Outreach & Medical Camps',
        'seo_description' => 'ASNEN community outreach and medical camps, including Komolion in Baringo County: assessment, registration, referral, and stigma reduction.',
        'canonical_path' => '/what-we-do/community-outreach-medical-camps',
    ],
];

$updatedPrograms = 0;
$updatedPages = 0;
$updatedSeo = 0;

foreach ($programmes as $definition) {
    $program = Program::updateOrCreate(
        ['slug' => $definition['slug']],
        [
            'title' => $definition['title'],
            'summary' => $definition['summary'],
            'body' => $definition['body'],
            'sort_order' => $definition['sort_order'],
            'status' => PublishStatus::Published,
            'published_at' => now(),
            'verification_status' => VerificationStatus::Verified,
        ]
    );
    $updatedPrograms++;

    $page = Page::query()->where('slug', 'what-we-do-'.$definition['slug'])->first();
    if ($page) {
        $page->update(['excerpt' => $definition['summary']]);
        $updatedPages++;
    } else {
        $page = Page::create([
            'title' => $definition['title'],
            'slug' => 'what-we-do-'.$definition['slug'],
            'template' => 'program',
            'excerpt' => $definition['summary'],
            'status' => PublishStatus::Published,
            'published_at' => now(),
            'verification_status' => VerificationStatus::Verified,
        ]);
        $updatedPages++;
    }

    $canonical = rtrim((string) config('app.url'), '/').$definition['canonical_path'];
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $definition['title'],
        'description' => $definition['seo_description'],
        'provider' => [
            '@type' => 'Organization',
            'name' => 'Africa Special Needs Education Network',
            'url' => rtrim((string) config('app.url'), '/'),
        ],
        'areaServed' => 'Africa',
        'url' => $canonical,
    ];

    SeoMeta::updateOrCreate(
        [
            'seoable_type' => $page->getMorphClass(),
            'seoable_id' => $page->id,
        ],
        [
            'title' => $definition['seo_title'],
            'description' => $definition['seo_description'],
            'canonical_url' => $canonical,
            'robots' => 'index,follow',
            'schema_json' => $schema,
        ]
    );
    $updatedSeo++;
}

$hubPage = Page::query()->where('slug', 'what-we-do')->first();
if ($hubPage) {
    $hubPage->update(['excerpt' => $hubExcerpt]);
    $updatedPages++;
} else {
    $hubPage = Page::create([
        'title' => 'What We Do',
        'slug' => 'what-we-do',
        'template' => 'default',
        'excerpt' => $hubExcerpt,
        'status' => PublishStatus::Published,
        'published_at' => now(),
        'verification_status' => VerificationStatus::Verified,
    ]);
    $updatedPages++;
}

$hubCanonical = rtrim((string) config('app.url'), '/').'/what-we-do';
SeoMeta::updateOrCreate(
    [
        'seoable_type' => $hubPage->getMorphClass(),
        'seoable_id' => $hubPage->id,
    ],
    [
        'title' => 'What We Do | Inclusive Education Programmes',
        'description' => 'ASNEN advances inclusion through seven interconnected programme areas, moving knowledge, capacity and care into homes, classrooms and communities across Africa.',
        'canonical_url' => $hubCanonical,
        'robots' => 'index,follow',
        'schema_json' => [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => 'What We Do',
            'description' => $hubExcerpt,
            'url' => $hubCanonical,
        ],
    ]
);
$updatedSeo++;

echo "OK programmes={$updatedPrograms} pages={$updatedPages} seo={$updatedSeo}\n";
