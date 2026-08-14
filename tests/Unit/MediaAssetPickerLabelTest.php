<?php

namespace Tests\Unit;

use App\Models\MediaAsset;
use Tests\TestCase;

class MediaAssetPickerLabelTest extends TestCase
{
    public function test_picker_label_uses_alt_and_folder(): void
    {
        $asset = new MediaAsset([
            'id' => 19,
            'alt' => 'Eva Naputuni Nyoike',
            'filename' => '03.jpg',
            'folder' => 'team',
            'path' => 'team/03.jpg',
        ]);

        $this->assertSame('Eva Naputuni Nyoike · team', $asset->pickerLabel());
    }

    public function test_picker_label_humanizes_filename_when_alt_is_missing(): void
    {
        $asset = new MediaAsset([
            'id' => 12,
            'alt' => '',
            'filename' => 'leaving-a-mark-where-it-matters.jpg',
            'folder' => 'events',
            'path' => 'events/leaving-a-mark-where-it-matters.jpg',
        ]);

        $this->assertSame('leaving a mark where it matters · events', $asset->pickerLabel());
    }
}
