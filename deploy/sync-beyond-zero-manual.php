<?php

/**
 * Record the caregivers manual launch with Beyond Zero on programme, toolkit, and partner copy.
 */

use App\Models\Partner;
use App\Models\Program;
use App\Models\Publication;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$program = Program::query()->where('slug', 'caregiver-training')->first();
if ($program) {
    $program->update([
        'body' => '<p>Our caregiver training equips parents, guardians, and professional caregivers with evidence-informed strategies, community connections, and ongoing support frameworks grounded in compassion and reciprocity.</p><p>ASNEN launched the caregivers manual with Beyond Zero. The Caregiver Support Toolkit is a training manual and facilitator guide for families of children with disability, with practical tools for homes and community settings.</p>',
    ]);
}

$publication = Publication::query()->where('slug', 'caregiver-support-toolkit')->first();
if ($publication) {
    $publication->update([
        'abstract' => 'Launched with Beyond Zero. Training manual and facilitator guide for caregivers of children with disability, with practical tools, conversation prompts, and session outlines for homes and community settings.',
    ]);
}

$partner = Partner::query()->where('slug', 'beyond-zero')->first();
if ($partner) {
    $partner->update([
        'description' => 'ASNEN launched the caregivers manual with Beyond Zero. The Caregiver Support Toolkit is available in Toolkits and Guides.',
    ]);
}

echo 'OK program='.($program?->id ?? 'missing')
    .' pub='.($publication?->id ?? 'missing')
    .' partner='.($partner?->id ?? 'missing')."\n";
