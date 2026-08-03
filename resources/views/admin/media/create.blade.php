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
            fileName: '',
            onFile(e) {
                const f = e.target.files && e.target.files[0];
                this.fileName = f ? f.name : '';
            }
        }"
    >
        @csrf
        @if(!empty($returnUrl))
            <input type="hidden" name="return" value="{{ $returnUrl }}">
        @endif

        <div class="admin-form__body">
            <header class="admin-form__header">
                <p class="admin-form__eyebrow">Media library</p>
                <h2 class="admin-form__title">Upload a new asset</h2>
                <p class="admin-form__intro">
                    Add photos, video, or PDFs for heroes, programs, stories, events, partners, and team.
                    After upload, attach the file on each content edit screen.
                </p>
            </header>

            <div class="admin-form__section">
                <p class="admin-form__section-title">File</p>

                <div class="admin-field">
                    <label for="file" class="admin-label">
                        Choose file <span class="req" aria-hidden="true">*</span>
                    </label>
                    <div class="admin-file">
                        <input
                            type="file"
                            name="file"
                            id="file"
                            accept="image/*,video/*,application/pdf"
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
                            <p class="admin-file__title">Drop a file here, or click to browse</p>
                            <p id="file-hint" class="admin-file__hint">Images, video, or PDF · max size per server limits</p>
                            <p class="admin-file__name" x-show="fileName" x-text="fileName" x-cloak></p>
                        </div>
                    </div>
                    @error('file')
                        <p class="admin-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="admin-form__section">
                <p class="admin-form__section-title">Details</p>

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
                <button type="submit" class="admin-btn-primary">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Upload media
                </button>
                <a href="{{ route('admin.media.index') }}" class="admin-btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
@endsection
