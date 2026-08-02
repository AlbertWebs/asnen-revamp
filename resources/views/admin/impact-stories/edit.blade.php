@extends('layouts.admin')

@section('title', $story->exists ? 'Edit Impact Story' : 'New Impact Story')
@section('heading', $story->exists ? 'Edit Impact Story' : 'New Impact Story')

@section('content')
    @if ($story->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $story, 'routePrefix' => 'impact-stories'])
            @can('approveSafeguarding', $story)
                <form method="POST" action="{{ route('admin.impact-stories.approve-safeguarding', $story) }}">
                    @csrf
                    <button type="submit" class="rounded-md border border-amber-400 bg-amber-50 px-3 py-1.5 text-sm text-amber-900 hover:bg-amber-100">Approve safeguarding</button>
                </form>
            @endcan
        </div>
    @endif

    <form method="POST" action="{{ $story->exists ? route('admin.impact-stories.update', $story) : route('admin.impact-stories.store') }}">
        @csrf
        @if ($story->exists) @method('PUT') @endif

        <div class="mb-4 flex justify-end">
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <label for="title" class="block text-sm font-medium text-charcoal-700">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $story->title) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="slug" class="mt-4 block text-sm font-medium text-charcoal-700">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $story->slug) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="summary" class="mt-4 block text-sm font-medium text-charcoal-700">Summary</label>
            <textarea name="summary" id="summary" rows="4" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('summary', $story->summary) }}</textarea>
            <label for="body" class="mt-4 block text-sm font-medium text-charcoal-700">Body</label>
            <textarea name="body" id="body" rows="4" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('body', $story->body) }}</textarea>
            <label for="location" class="mt-4 block text-sm font-medium text-charcoal-700">Location</label>
            <input type="text" name="location" id="location" value="{{ old('location', $story->location) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="story_date" class="mt-4 block text-sm font-medium text-charcoal-700">Story Date</label>
            <input type="date" name="story_date" id="story_date" value="{{ old('story_date', optional($story->story_date)->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="challenges" class="mt-4 block text-sm font-medium text-charcoal-700">Challenges</label>
            <textarea name="challenges" id="challenges" rows="3" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('challenges', $story->challenges) }}</textarea>
            <label for="learnings" class="mt-4 block text-sm font-medium text-charcoal-700">Learnings</label>
            <textarea name="learnings" id="learnings" rows="3" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('learnings', $story->learnings) }}</textarea>
            <label for="next_steps" class="mt-4 block text-sm font-medium text-charcoal-700">Next Steps</label>
            <textarea name="next_steps" id="next_steps" rows="3" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('next_steps', $story->next_steps) }}</textarea>

            @include('admin.partials.media-picker', [
                'name' => 'featured_image_id',
                'value' => $story->featured_image_id,
                'label' => 'Story image',
                'help' => 'Shown on the homepage feature and impact hub.',
            ])
        </div>
    </form>

    @if($story->exists && $gallery)
        @include('admin.partials.gallery-dropzone', [
            'gallery' => $gallery,
            'heading' => 'Story gallery',
            'help' => 'Drop all case-study photos here. They appear in a lightbox gallery on the public Komolion / story page. Edit captions below each image.',
        ])
        <p class="mt-3 max-w-4xl text-xs text-charcoal-500">
            Linked gallery:
            <a href="{{ route('admin.galleries.edit', $gallery) }}" class="font-medium text-forest-700 hover:underline">{{ $gallery->title }}</a>
        </p>
    @elseif($story->exists)
        <p class="mt-6 max-w-3xl text-sm text-charcoal-500">Save the story once more to enable the gallery dropzone.</p>
    @else
        <p class="mt-6 max-w-3xl text-sm text-charcoal-500">Save the story first, then drop gallery images here.</p>
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