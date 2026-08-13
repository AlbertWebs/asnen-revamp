<?php

/**
 * Mega-menu column groupings inspired by CTC-style navigation.
 * Keys are parent nav labels (case-insensitive match).
 * Each column has a title and links matched by child URL (preferred) or label.
 */
return [
    'About' => [
        'max_width' => '820px',
        'columns' => [
            [
                'title' => 'The organisation',
                'items' => [
                    ['url' => '/about/who-we-are', 'label' => 'Who We Are', 'desc' => 'Identity, vision, and how the network began.'],
                ],
            ],
            [
                'title' => 'People',
                'items' => [
                    ['url' => '/about/leadership', 'label' => 'Leadership & Governance', 'desc' => 'Meet the team and how we stay accountable.'],
                ],
            ],
            [
                'title' => 'Network',
                'items' => [
                    ['url' => '/about/partners', 'label' => 'Collaborators', 'desc' => 'Organisations we walk with.'],
                ],
            ],
        ],
    ],

    'What We Do' => [
        'max_width' => '960px',
        'columns' => [
            [
                'title' => 'Education & care',
                'items' => [
                    ['url' => '/what-we-do/inclusive-education', 'desc' => 'Teacher training, classroom practice, and school–family partnership for every learner.'],
                    ['url' => '/what-we-do/caregiver-training', 'desc' => 'Evidence-informed skills, peer support, and ongoing frameworks for caregivers.'],
                    ['url' => '/what-we-do/early-identification-intervention', 'desc' => 'Faster pathways to assessment, referral, and early support.'],
                ],
            ],
            [
                'title' => 'Advocacy & community',
                'items' => [
                    ['url' => '/what-we-do/disability-awareness-advocacy', 'desc' => 'Awareness, coalitions, and lived experience at the centre of advocacy.'],
                    ['url' => '/what-we-do/social-inclusion', 'desc' => 'Community spaces where children and young adults belong fully.'],
                    ['url' => '/what-we-do/community-outreach-medical-camps', 'desc' => 'Medical camps, registration, and stigma reduction in the field.'],
                ],
            ],
            [
                'title' => 'Systems',
                'items' => [
                    ['url' => '/what-we-do/research-policy-partnerships', 'desc' => 'African evidence, policy dialogue, and lasting partnerships.'],
                    ['url' => '/what-we-do', 'desc' => 'Seven interconnected programme areas for inclusion.', 'label' => 'All programmes'],
                ],
            ],
        ],
    ],

    'Impact' => [
        'max_width' => '720px',
        'columns' => [
            [
                'title' => 'Stories & evidence',
                'items' => [
                    ['url' => '/impact', 'desc' => 'Impact overview and highlights.'],
                    ['url' => '/impact/komolion', 'desc' => 'The Komolion community story.'],
                    ['url' => '/impact/stories', 'desc' => 'Families and caregivers sharing outcomes.'],
                ],
            ],
            [
                'title' => 'Reports',
                'items' => [
                    ['url' => '/impact/reports', 'desc' => 'Published impact reports.'],
                    ['url' => '/impact/regions', 'desc' => 'Where the work reaches.'],
                ],
            ],
        ],
    ],

    'Events & Learning' => [
        'max_width' => '640px',
        'columns' => [
            [
                'title' => 'Events',
                'items' => [
                    ['url' => '/events-learning/upcoming', 'desc' => 'What is coming next.'],
                    ['url' => '/events-learning/past', 'desc' => 'Past gatherings and camps.'],
                ],
            ],
            [
                'title' => 'Learning',
                'items' => [
                    ['url' => '/events-learning/webinars', 'desc' => 'Open webinars and recordings.'],
                    ['url' => '/events-learning/ubuntu-conference', 'desc' => 'Continental Ubuntu conference.'],
                ],
            ],
        ],
    ],

    'Resources' => [
        'max_width' => '720px',
        'columns' => [
            [
                'title' => 'Library',
                'items' => [
                    ['url' => '/resources/publications', 'desc' => 'Reports and publications.'],
                    ['url' => '/resources/toolkits', 'desc' => 'Practical guides for caregivers and educators.'],
                    ['url' => '/resources/webinars', 'desc' => 'Video and webinar library.'],
                ],
            ],
            [
                'title' => 'Updates',
                'items' => [
                    ['url' => '/resources/news', 'desc' => 'News and insights from ASNEN.'],
                    ['url' => '/resources/gallery', 'desc' => 'Photos from programmes and events.'],
                ],
            ],
        ],
    ],

    'Get Involved' => [
        'max_width' => '720px',
        'columns' => [
            [
                'title' => 'Join us',
                'items' => [
                    ['url' => '/get-involved/membership', 'desc' => 'Belong to the network as a member.'],
                    ['url' => '/get-involved/volunteer', 'desc' => 'Give time and skills.'],
                ],
            ],
            [
                'title' => 'Support',
                'items' => [
                    ['url' => '/get-involved/partner', 'desc' => 'Partner with ASNEN on programmes.'],
                    ['url' => '/get-involved/donate', 'desc' => 'Support a programme or campaign.'],
                ],
            ],
        ],
    ],
];
