<?php

use App\Enums\ConsentStatus;
use App\Models\FormDefinition;
use App\Models\MediaAsset;
use App\Models\Publication;
use Illuminate\Support\Facades\File;

$path = 'resources/caregiver-support-toolkit.jpg';
$abs = storage_path('app/public/'.$path);

if (! File::exists($abs)) {
    throw new RuntimeException('Cover missing: '.$abs);
}

$media = MediaAsset::updateOrCreate(
    ['path' => $path, 'disk' => 'public'],
    [
        'filename' => basename($path),
        'mime' => File::mimeType($abs) ?: 'image/jpeg',
        'size' => File::size($abs),
        'alt' => 'Caregiver Support Toolkit cover',
        'folder' => 'resources',
        'is_private' => false,
        'consent_status' => ConsentStatus::NotRequired,
        'credit' => 'Africa Special Needs Education Network (asnenafrica.org)',
    ]
);

$pub = Publication::where('slug', 'caregiver-support-toolkit')->firstOrFail();
$pub->update([
    'cover_id' => $media->id,
    'abstract' => 'Launched with Beyond Zero. Training manual and facilitator guide for caregivers of children with disability, with practical tools, conversation prompts, and session outlines for homes and community settings.',
]);

FormDefinition::updateOrCreate(
    ['slug' => 'toolkit-request'],
    [
        'name' => 'Toolkit Request',
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
        'is_active' => true,
    ]
);

echo "OK cover={$media->id} pub={$pub->id}\n";
