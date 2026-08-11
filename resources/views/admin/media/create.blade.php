@extends('layouts.admin')

@section('title', 'Upload Media')
@section('heading', 'Upload Media')

@section('content')
    <div class="mb-5">
        <a href="{{ route('admin.media.index') }}" class="admin-btn-ghost !min-h-0 !px-0 !py-0 text-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to media library
        </a>
    </div>

    <form
        method="POST"
        action="{{ route('admin.media.store') }}"
        enctype="multipart/form-data"
        class="admin-form"
        x-data="{
            files: [],
            previews: [],
            dragging: false,
            revokePreviews() {
                this.previews.forEach((p) => { if (p.url) URL.revokeObjectURL(p.url); });
                this.previews = [];
            },
            setFiles(fileList) {
                this.revokePreviews();
                const list = Array.from(fileList || []);
                this.files = list;
                this.previews = list.map((file) => ({
                    name: file.name,
                    size: file.size,
                    type: file.type || '',
                    url: file.type && file.type.startsWith('image/') ? URL.createObjectURL(file) : null,
                }));
            },
            onFile(e) {
                this.setFiles(e.target.files);
            },
            onDrop(e) {
                this.dragging = false;
                const dropped = e.dataTransfer && e.dataTransfer.files;
                if (!dropped || !dropped.length) return;
                const input = this.$refs.fileInput;
                if (!input) return;
                const dt = new DataTransfer();
                Array.from(dropped).forEach((f) => dt.items.add(f));
                input.files = dt.files;
                this.setFiles(input.files);
            },
            removeAt(index) {
                const input = this.$refs.fileInput;
                if (!input) return;
                const dt = new DataTransfer();
                Array.from(this.files).forEach((f, i) => { if (i !== index) dt.items.add(f); });
                input.files = dt.files;
                this.setFiles(input.files);
            },
            formatSize(bytes) {
                if (!bytes && bytes !== 0) return '';
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                return (bytes / 1048576).toFixed(1) + ' MB';
            },
            isPdf(item) {
                const type = (item.type || '').toLowerCase();
                const name = (item.name || '').toLowerCase();
                return type.includes('pdf') || name.endsWith('.pdf');
            },
            isWord(item) {
                const type = (item.type || '').toLowerCase();
                const name = (item.name || '').toLowerCase();
                return type.includes('word')
                    || type.includes('officedocument.wordprocessingml')
                    || name.endsWith('.doc')
                    || name.endsWith('.docx');
            },
            formatLabel(item) {
                if (this.isPdf(item)) return 'PDF';
                if (this.isWord(item)) return 'Word';
                return 'File';
            }
        }"
        @dragover.prevent="dragging = true"
        @dragleave.prevent="dragging = false"
        @drop.prevent="onDrop($event)"
    >
        @csrf
        @if(!empty($returnUrl))
            <input type="hidden" name="return" value="{{ $returnUrl }}">
        @endif

        <div class="admin-form__body">
            <header class="admin-form__header">
                <p class="admin-form__eyebrow">Media library</p>
                <h2 class="admin-form__title">Upload media</h2>
                <p class="admin-form__intro">
                    Drop one or many photos, videos, PDFs, or Word documents. After upload, attach them on each content edit screen.
                </p>
            </header>

            <div class="admin-form__section">
                <p class="admin-form__section-title">Files</p>

                <div class="admin-field">
                    <label for="files" class="admin-label">
                        Choose files <span class="req" aria-hidden="true">*</span>
                    </label>
                    <div class="admin-file" :class="{ 'is-dragging': dragging }">
                        <input
                            type="file"
                            name="files[]"
                            id="files"
                            x-ref="fileInput"
                            accept="image/*,video/*,application/pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                            multiple
                            required
                            @change="onFile($event)"
                            aria-describedby="file-hint"
                        >
                        <div class="pointer-events-none">
                            <span class="admin-file__icon" aria-hidden="true">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            </span>
                            <p class="admin-file__title">Drop files here, or click to browse</p>
                            <p id="file-hint" class="admin-file__hint">Select multiple images, video, PDFs, or Word files · max 40 files · 10&nbsp;MB each · duplicates are skipped automatically</p>
                            <p class="admin-file__name" x-show="files.length" x-cloak>
                                <span x-text="files.length + (files.length === 1 ? ' file selected' : ' files selected')"></span>
                            </p>
                        </div>
                    </div>
                    @error('files')
                        <p class="admin-error">{{ $message }}</p>
                    @enderror
                    @error('files.*')
                        <p class="admin-error">{{ $message }}</p>
                    @enderror
                    @error('file')
                        <p class="admin-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="admin-media-grid mt-4" x-show="previews.length" x-cloak>
                    <template x-for="(item, index) in previews" :key="item.name + '-' + index">
                        <div class="admin-media-thumb !cursor-default">
                            <template x-if="item.url">
                                <img :src="item.url" :alt="item.name" class="admin-media-thumb__img">
                            </template>
                            <template x-if="!item.url">
                                <span
                                    class="admin-file-icon"
                                    :class="{
                                        'admin-file-icon--pdf': isPdf(item),
                                        'admin-file-icon--word': isWord(item),
                                        'admin-file-icon--file': !isPdf(item) && !isWord(item),
                                    }"
                                    role="img"
                                    :aria-label="formatLabel(item) + ' document'"
                                >
                                    <svg class="admin-file-icon__doc" viewBox="0 0 48 56" fill="none" aria-hidden="true">
                                        <path d="M8 4h22l12 12v36a4 4 0 01-4 4H8a4 4 0 01-4-4V8a4 4 0 014-4z" fill="currentColor" opacity="0.12"/>
                                        <path d="M8 4h22l12 12v36a4 4 0 01-4 4H8a4 4 0 01-4-4V8a4 4 0 014-4z" stroke="currentColor" stroke-width="2.25" stroke-linejoin="round"/>
                                        <path d="M30 4v10a2 2 0 002 2h10" stroke="currentColor" stroke-width="2.25" stroke-linejoin="round"/>
                                        <path d="M14 28h20M14 36h16M14 44h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                    <span class="admin-file-icon__badge" x-text="formatLabel(item)"></span>
                                </span>
                            </template>
                            <span class="admin-media-thumb__caption">
                                <span x-text="item.name"></span>
                                <span class="block text-charcoal/40" x-text="formatSize(item.size)"></span>
                            </span>
                            <button
                                type="button"
                                class="admin-media-thumb__remove"
                                @click.prevent="removeAt(index)"
                                :aria-label="'Remove ' + item.name"
                            >
                                <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="admin-form__section">
                <p class="admin-form__section-title">Details</p>
                <p class="admin-hint mb-3">Optional shared alt text and caption are applied to every file in this batch. You can refine each asset afterward.</p>

                <div class="admin-field">
                    <label for="alt" class="admin-label">Alt text</label>
                    <p class="admin-hint">Describe the image for screen readers and when the file can’t load.</p>
                    <input
                        type="text"
                        name="alt"
                        id="alt"
                        value="{{ old('alt') }}"
                        class="admin-input"
                        placeholder="e.g. Students collaborating in a classroom workshop"
                        @error('alt') aria-invalid="true" @enderror
                    >
                    @error('alt')
                        <p class="admin-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="admin-field">
                    <label for="caption" class="admin-label">Caption</label>
                    <textarea
                        name="caption"
                        id="caption"
                        rows="3"
                        class="admin-textarea"
                        placeholder="Optional caption shown with the media"
                        @error('caption') aria-invalid="true" @enderror
                    >{{ old('caption') }}</textarea>
                    @error('caption')
                        <p class="admin-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="admin-form__section">
                <p class="admin-form__section-title">Organisation</p>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="admin-field">
                        <label for="folder" class="admin-label">Folder</label>
                        <select name="folder" id="folder" class="admin-select">
                            @foreach (['hero','programs','stories','events','partners','team','resources','gallery','uploads'] as $folder)
                                <option value="{{ $folder }}" @selected(old('folder', $defaultFolder ?? 'uploads') === $folder)>{{ $folder }}</option>
                            @endforeach
                        </select>
                        <p class="admin-hint">Keeps the library organised by use.</p>
                    </div>

                    <div class="admin-field">
                        <label for="consent_status" class="admin-label">Consent status</label>
                        <select name="consent_status" id="consent_status" class="admin-select">
                            @foreach (\App\Enums\ConsentStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected(old('consent_status', 'pending') === $status->value)>
                                    {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                                </option>
                            @endforeach
                        </select>
                        <p class="admin-hint">Required before publishing identifiable people.</p>
                    </div>
                </div>

                <div class="admin-callout">
                    Prefer clear filenames and meaningful alt text. You can refine consent notes and credit after upload.
                </div>
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary" :disabled="files.length === 0">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span x-text="files.length > 1 ? ('Upload ' + files.length + ' files') : 'Upload media'"></span>
                </button>
                <a href="{{ route('admin.media.index') }}" class="admin-btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
@endsection
