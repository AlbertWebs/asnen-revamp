{{--
  Reusable media picker for admin forms.
  Expects: $name, $value (id), optional $label, $help, $type (image|file), $folder
--}}
@php
    use App\Models\MediaAsset;

    $name = $name ?? 'featured_image_id';
    $label = $label ?? 'Featured image';
    $type = $type ?? 'image';
    $isFile = $type === 'file';
    $folder = $folder ?? ($isFile ? 'resources' : 'programs');
    $help = $help ?? ($isFile
        ? 'Upload a PDF here, or choose one already in the Media Library.'
        : 'Click a thumbnail to choose the image. Names include the folder so similar files are easier to tell apart.');
    $selectedId = old($name, $value ?? null);
    $mediaQuery = MediaAsset::query()
        ->when(
            $isFile,
            fn ($q) => $q->where(function ($inner) {
                $inner->where('mime', 'application/pdf')
                    ->orWhere('mime', 'like', 'application/%')
                    ->orWhere('filename', 'like', '%.pdf');
            }),
            fn ($q) => $q->where('mime', 'like', 'image/%')
        );
    $mediaOptions = (clone $mediaQuery)
        ->latest('id')
        ->limit(250)
        ->get();

    if ($selectedId && ! $mediaOptions->contains('id', (int) $selectedId)) {
        $current = MediaAsset::query()->find($selectedId);
        if ($current) {
            $mediaOptions = $mediaOptions->prepend($current)->unique('id')->values();
        }
    }

    $optionList = $mediaOptions->map(fn ($m) => [
        'id' => (string) $m->id,
        'label' => $m->pickerLabel(),
        'filename' => $m->filename,
        'folder' => $m->folder,
        'url' => $m->publicUrl(),
    ])->values()->all();
    $pickerId = 'media-picker-'.\Illuminate\Support\Str::slug($name);
    $canUpload = auth()->user()?->can('media.upload');
@endphp

<div
    class="mt-4 space-y-3"
    x-data="{
        selected: @js($selectedId ? (string) $selectedId : ''),
        options: @js($optionList),
        query: '',
        isFile: @js($isFile),
        folder: @js($folder),
        uploadUrl: @js(route('admin.media.store')),
        csrf: @js(csrf_token()),
        canUpload: @js((bool) $canUpload),
        uploading: false,
        dragging: false,
        error: '',
        message: '',
        get previewUrl() {
            if (this.isFile || !this.selected) return null;
            const match = this.options.find((o) => o.id === String(this.selected));
            return match?.url || null;
        },
        get selectedOption() {
            if (!this.selected) return null;
            return this.options.find((o) => o.id === String(this.selected)) || null;
        },
        get filtered() {
            const q = this.query.trim().toLowerCase();
            if (!q) return this.options;
            return this.options.filter((o) => {
                return [o.label, o.filename, o.folder].filter(Boolean).join(' ').toLowerCase().includes(q);
            });
        },
        choose(id) {
            this.selected = String(id);
        },
        onFiles(fileList) {
            const files = Array.from(fileList || []);
            if (!files.length) return;
            const file = files[0];
            if (this.isFile) {
                if (file.type && !file.type.includes('pdf') && !file.type.startsWith('application/')) {
                    this.error = 'Please choose a PDF or document file.';
                    return;
                }
            } else if (!file.type.startsWith('image/')) {
                this.error = 'Please choose an image file.';
                return;
            }
            this.upload(file);
        },
        async upload(file) {
            if (!this.canUpload || this.uploading) return;
            this.uploading = true;
            this.error = '';
            this.message = '';

            const formData = new FormData();
            formData.append('file', file);
            formData.append('folder', this.folder);
            formData.append('alt', file.name.replace(/\.[^.]+$/, '').replace(/[-_]+/g, ' '));

            try {
                const response = await fetch(this.uploadUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrf,
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    const firstError = Object.values(data.errors || {})[0]?.[0];
                    throw new Error(data.message || firstError || 'Upload failed.');
                }

                const asset = data.asset;
                if (!asset?.id) throw new Error('Upload succeeded but no media was returned.');

                const option = {
                    id: String(asset.id),
                    label: asset.label,
                    filename: asset.filename || file.name,
                    folder: asset.folder || this.folder,
                    url: asset.url,
                };
                this.options = [option, ...this.options.filter((o) => o.id !== option.id)];
                this.selected = option.id;
                this.query = '';
                this.message = data.message || 'Uploaded and selected. Save the form to keep it.';
            } catch (err) {
                this.error = err.message || 'Upload failed.';
            } finally {
                this.uploading = false;
            }
        }
    }"
>
    <div class="flex flex-wrap items-end justify-between gap-2">
        <label for="{{ $pickerId }}-search" class="admin-label">{{ $label }}</label>
        @can('media.upload')
            <a href="{{ route('admin.media.create', ['return' => url()->full(), 'folder' => $folder]) }}" class="text-xs font-medium text-brand hover:underline">
                Open Media Library →
            </a>
        @endcan
    </div>

    @can('media.upload')
        <label
            class="admin-file block cursor-pointer"
            @dragover.prevent="dragging = true"
            @dragleave.prevent="dragging = false"
            @drop.prevent="dragging = false; onFiles($event.dataTransfer.files)"
            :class="{ 'ring-2 ring-brand/40 border-brand': dragging }"
        >
            <input
                type="file"
                @change="onFiles($event.target.files); $event.target.value = ''"
                accept="{{ $isFile ? '.pdf,application/pdf' : 'image/*' }}"
                :disabled="uploading"
            >
            <p class="admin-file__title" x-text="uploading ? 'Uploading…' : (isFile ? 'Drop a PDF here or click to upload' : 'Drop an image here or click to upload')"></p>
            <p class="admin-file__hint" x-show="!uploading">Max 10MB. Uploads to the “{{ $folder }}” folder.</p>
        </label>
        <div class="h-1.5 overflow-hidden rounded-full bg-sand" x-show="uploading" x-cloak>
            <div class="h-full w-2/3 animate-pulse rounded-full bg-brand"></div>
        </div>
        <p class="text-xs font-medium text-brand" x-show="message" x-text="message" x-cloak></p>
        <p class="admin-error" x-show="error" x-text="error" x-cloak></p>
    @endcan

    <input type="hidden" name="{{ $name }}" :value="selected">

    @unless($isFile)
        <div class="overflow-hidden rounded-xl border border-charcoal/10 bg-sand/50" style="aspect-ratio: 16/9; max-width: 28rem;">
            <template x-if="previewUrl">
                <img :src="previewUrl" :alt="selectedOption?.label || ''" class="h-full w-full object-cover">
            </template>
            <div x-show="!previewUrl" class="flex h-full min-h-[8rem] items-center justify-center px-4 text-center text-xs text-charcoal/50">
                Placeholder will show on the public site until you choose an image.
            </div>
        </div>
        <p class="text-sm font-medium text-charcoal" x-show="selectedOption" x-cloak x-text="selectedOption?.label"></p>
    @endunless

    <div class="flex flex-wrap items-center gap-2">
        <input
            type="search"
            id="{{ $pickerId }}-search"
            x-model="query"
            placeholder="{{ $isFile ? 'Search files by name or folder' : 'Search images by name or folder' }}"
            class="admin-input max-w-md"
            autocomplete="off"
        >
        <button
            type="button"
            class="admin-btn-ghost !min-h-0 !px-3 !py-1.5 text-sm"
            x-show="selected"
            x-cloak
            @click="selected = ''"
        >
            Clear selection
        </button>
    </div>

    @if($isFile)
        <select
            id="{{ $pickerId }}"
            x-model="selected"
            class="admin-select"
        >
            <option value="">No file attached</option>
            <template x-for="opt in filtered" :key="opt.id">
                <option :value="opt.id" x-text="opt.label" :selected="selected === opt.id"></option>
            </template>
        </select>
    @else
        <p class="admin-hint">Click a thumbnail to select it. {{ $help }}</p>
        <div class="admin-media-grid max-h-[28rem] overflow-y-auto rounded-xl border border-charcoal/10 bg-white p-3">
            <template x-for="opt in filtered" :key="opt.id">
                <button
                    type="button"
                    class="admin-media-thumb"
                    :class="{ 'is-selected': selected === opt.id }"
                    @click="choose(opt.id)"
                    :aria-pressed="selected === opt.id ? 'true' : 'false'"
                >
                    <template x-if="opt.url">
                        <img :src="opt.url" :alt="opt.label" loading="lazy" class="admin-media-thumb__img">
                    </template>
                    <template x-if="!opt.url">
                        <span class="admin-media-thumb__fallback" aria-hidden="true">No preview</span>
                    </template>
                    <span class="admin-media-thumb__caption" x-text="opt.label"></span>
                    <span class="admin-media-thumb__check" aria-hidden="true">
                        <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </span>
                </button>
            </template>
        </div>
        <p class="admin-hint" x-text="filtered.length + ' image' + (filtered.length === 1 ? '' : 's') + ' shown'"></p>
    @endif
</div>
