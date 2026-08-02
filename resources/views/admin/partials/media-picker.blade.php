{{--
  Reusable media picker for admin forms.
  Expects: $name, $value (id), optional $label, $help, $type (image|file)
--}}
@php
    use App\Models\MediaAsset;

    $name = $name ?? 'featured_image_id';
    $label = $label ?? 'Featured image';
    $type = $type ?? 'image';
    $isFile = $type === 'file';
    $help = $help ?? ($isFile
        ? 'Upload PDFs in Media Library, then select one here.'
        : 'Upload images in Media Library, then select one here.');
    $selectedId = old($name, $value ?? null);
    $mediaOptions = MediaAsset::query()
        ->when(
            $isFile,
            fn ($q) => $q->where(function ($inner) {
                $inner->where('mime', 'application/pdf')
                    ->orWhere('mime', 'like', 'application/%')
                    ->orWhere('filename', 'like', '%.pdf');
            }),
            fn ($q) => $q->where('mime', 'like', 'image/%')
        )
        ->latest('id')
        ->limit(250)
        ->get();
    $previewMap = $mediaOptions->mapWithKeys(fn ($m) => [(string) $m->id => $m->publicUrl()])->all();
    $pickerId = 'media-picker-'.\Illuminate\Support\Str::slug($name);
@endphp

<div
    class="mt-4"
    x-data="{
        selected: @js($selectedId ? (string) $selectedId : ''),
        urls: @js($previewMap),
        isFile: @js($isFile),
        get previewUrl() { return (!this.isFile && this.selected) ? (this.urls[this.selected] || null) : null; }
    }"
>
    <div class="flex flex-wrap items-end justify-between gap-2">
        <label for="{{ $pickerId }}" class="block text-sm font-medium text-charcoal-700">{{ $label }}</label>
        @can('media.upload')
            <a href="{{ route('admin.media.create', ['return' => url()->full(), 'folder' => $isFile ? 'resources' : 'uploads']) }}" class="text-xs font-medium text-forest-700 hover:underline">
                Upload new {{ $isFile ? 'file' : 'image' }} →
            </a>
        @endcan
    </div>

    <select
        name="{{ $name }}"
        id="{{ $pickerId }}"
        x-model="selected"
        class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500"
    >
        <option value="">{{ $isFile ? 'No file attached' : 'No image (placeholder on site)' }}</option>
        @foreach($mediaOptions as $asset)
            <option value="{{ $asset->id }}">
                {{ ($asset->alt ?: $asset->filename).' (#'.$asset->id.')' }}
            </option>
        @endforeach
    </select>

    <p class="mt-1 text-xs text-charcoal-500">{{ $help }}</p>

    @unless($isFile)
        <div class="mt-3 overflow-hidden rounded-md border border-charcoal-200 bg-charcoal-50" style="aspect-ratio: 16/9; max-width: 28rem;">
            <template x-if="previewUrl">
                <img :src="previewUrl" alt="" class="h-full w-full object-cover">
            </template>
            <div x-show="!previewUrl" class="flex h-full min-h-[8rem] items-center justify-center px-4 text-center text-xs text-charcoal-500">
                Placeholder will show on the public site until you choose an image.
            </div>
        </div>
    @endunless
</div>
