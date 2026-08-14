<?php

use App\Enums\ConsentStatus;
use App\Enums\PublishStatus;
use App\Enums\VerificationStatus;
use App\Models\MediaAsset;
use App\Models\Partner;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$collaborators = [
    [
        'name' => 'CURE International',
        'slug' => 'cure-international',
        'description' => 'CURE',
        'logo' => 'partners/cure.jpeg',
        'source' => public_path('collborators/cure.jpeg'),
    ],
    [
        'name' => 'Noonkera',
        'slug' => 'noonkera',
        'description' => 'education is my foundation',
        'logo' => 'partners/noonkera.jpeg',
        'source' => public_path('collborators/noonkera.jpeg'),
    ],
];

$maxSort = (int) (Partner::query()->max('sort_order') ?? 0);

foreach ($collaborators as $definition) {
    $dest = storage_path('app/public/'.$definition['logo']);
    File::ensureDirectoryExists(dirname($dest));

    if (File::exists($definition['source'])) {
        File::copy($definition['source'], $dest);
    }

    if (! File::exists($dest)) {
        throw new RuntimeException('Logo missing: '.$definition['logo']);
    }

    $mediaPayload = [
        'filename' => basename($definition['logo']),
        'mime' => File::mimeType($dest) ?: 'image/jpeg',
        'size' => File::size($dest),
        'alt' => $definition['name'].' logo',
        'folder' => 'partners',
        'is_private' => false,
        'consent_status' => ConsentStatus::NotRequired,
        'credit' => 'Africa Special Needs Education Network (asnenafrica.org)',
    ];

    if (Schema::hasColumn('media_assets', 'content_hash')) {
        $mediaPayload['content_hash'] = hash_file('sha256', $dest);
    }

    $media = MediaAsset::updateOrCreate(
        ['path' => $definition['logo'], 'disk' => 'public'],
        $mediaPayload
    );

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
        'logo_id' => $media->id,
        'status' => PublishStatus::Published,
        'published_at' => $partner?->published_at ?? now(),
        'verification_status' => VerificationStatus::Verified,
    ];

    if ($partner) {
        if ($partner->trashed()) {
            $partner->restore();
        }
        if (! $partner->sort_order) {
            $maxSort++;
            $payload['sort_order'] = $maxSort;
        }
        $partner->update($payload);
    } else {
        $maxSort++;
        $payload['sort_order'] = $maxSort;
        $partner = Partner::create($payload);
    }

    echo "synced {$partner->slug} id={$partner->id} logo={$media->id}\n";
}
