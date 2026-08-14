@extends('layouts.admin')

@section('title', $story->exists ? 'Edit Success Story' : 'New Success Story')
@section('heading', $story->exists ? 'Edit Success Story' : 'New Success Story')

@section('content')
    @if ($story->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $story, 'routePrefix' => 'impact-stories'])
            @can('approveSafeguarding', $story)
                <form method="POST" action="{{ route('admin.impact-stories.approve-safeguarding', $story) }}">
                    @csrf
                    <button type="submit" class="admin-btn-secondary border-amber-300 bg-amber-50 text-amber-900 hover:bg-amber-100">Approve safeguarding</button>
                </form>
            @endcan
            @if($story->isPublished())
                <a href="{{ $story->publicUrl() }}" class="admin-btn-secondary" target="_blank" rel="noopener">View on site</a>
            @endif
        </div>
        @if($story->slug === \App\Models\ImpactStory::KOMOLION_SLUG)
            <div class="admin-callout mb-4">
                This is the featured Komolion case study. It appears first on Success Stories and no longer has a separate menu item.
            </div>
        @endif
    @endif

    <form method="POST" action="{{ $story->exists ? route('admin.impact-stories.update', $story) : route('admin.impact-stories.store') }}" class="admin-form admin-form--wide">
        @csrf
        @if ($story->exists) @method('PUT') @endif

        <div class="admin-form__body">
            <header class="admin-form__header">
                <p class="admin-form__eyebrow">Success stories</p>
                <h2 class="admin-form__title">{{ $story->exists ? 'Edit success story' : 'New success story' }}</h2>
            </header>

            <div class="admin-form__section">
                <div class="admin-field">
                    <label for="title" class="admin-label">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $story->title) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="slug" class="admin-label">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $story->slug) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="summary" class="admin-label">Summary</label>
                    <textarea name="summary" id="summary" rows="4" class="admin-textarea">{{ old('summary', $story->summary) }}</textarea>
                </div>
                <div class="admin-field">
                    <label for="body" class="admin-label">Body</label>
                    <textarea name="body" id="body" rows="4" class="admin-textarea">{{ old('body', $story->body) }}</textarea>
                </div>
                <div class="admin-field">
                    <label for="location" class="admin-label">Location</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $story->location) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="story_date" class="admin-label">Story date</label>
                    <input type="date" name="story_date" id="story_date" value="{{ old('story_date', optional($story->story_date)->format('Y-m-d')) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="challenges" class="admin-label">Challenges</label>
                    <textarea name="challenges" id="challenges" rows="3" class="admin-textarea">{{ old('challenges', $story->challenges) }}</textarea>
                </div>
                <div class="admin-field">
                    <label for="learnings" class="admin-label">Learnings</label>
                    <textarea name="learnings" id="learnings" rows="3" class="admin-textarea">{{ old('learnings', $story->learnings) }}</textarea>
                </div>
                <div class="admin-field">
                    <label for="next_steps" class="admin-label">Next steps</label>
                    <textarea name="next_steps" id="next_steps" rows="3" class="admin-textarea">{{ old('next_steps', $story->next_steps) }}</textarea>
                </div>

                @include('admin.partials.media-picker', [
                    'name' => 'featured_image_id',
                    'value' => $story->featured_image_id,
                    'label' => 'Story image',
                    'help' => 'Shown on the homepage feature and impact hub.',
                ])
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary">Save</button>
            </div>
        </div>
    </form>

    @if($story->exists && $gallery)
        @include('admin.partials.gallery-dropzone', [
            'gallery' => $gallery,
            'albums' => \App\Models\Gallery::orderedForPicker(),
            'heading' => 'Story gallery',
            'help' => 'Drop case-study photos here. They appear in the photo gallery on the public success story page. Use Album to move a photo into another gallery.',
        ])
        <p class="admin-hint mt-3 max-w-4xl">
            Linked gallery:
            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="font-medium text-brand hover:underline">{{ $gallery->title }}</a>
        </p>
    @elseif($story->exists)
        <p class="admin-hint mt-6 max-w-3xl">Save the story once more to enable the gallery dropzone.</p>
    @else
        <p class="admin-hint mt-6 max-w-3xl">Save the story first, then drop gallery images here.</p>
    @endif

    @if($story->exists)
        @include('admin.partials.partner-logos-dropzone', [
            'story' => $story,
            'availablePartners' => $availablePartners ?? collect(),
            'heading' => 'Partners on the day',
            'help' => 'Upload logos for partners who took part on the day. They show on the public story page under Partners on the day.',
        ])
    @endif
@endsection
