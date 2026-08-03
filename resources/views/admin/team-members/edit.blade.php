@extends('layouts.admin')

@section('title', $member->exists ? 'Edit Team Member' : 'New Team Member')
@section('heading', $member->exists ? 'Edit Team Member' : 'New Team Member')

@section('content')
    @if ($member->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $member, 'routePrefix' => 'team-members'])
        </div>
    @endif

    <form method="POST" action="{{ $member->exists ? route('admin.team-members.update', $member) : route('admin.team-members.store') }}">
        @csrf
        @if ($member->exists) @method('PUT') @endif

        <div class="mb-4 flex justify-end">
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl space-y-4 rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <div>
                <label for="name" class="block text-sm font-medium text-charcoal-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $member->name) }}" required class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            </div>
            <div>
                <label for="slug" class="block text-sm font-medium text-charcoal-700">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $member->slug) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            </div>
            <div>
                <label for="title_role" class="block text-sm font-medium text-charcoal-700">Role / title</label>
                <input type="text" name="title_role" id="title_role" value="{{ old('title_role', $member->title_role) }}" required class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            </div>
            <div>
                <label for="bio" class="block text-sm font-medium text-charcoal-700">Bio</label>
                <textarea name="bio" id="bio" rows="5" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('bio', $member->bio) }}</textarea>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="email" class="block text-sm font-medium text-charcoal-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $member->email) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
                <div>
                    <label for="linkedin_url" class="block text-sm font-medium text-charcoal-700">LinkedIn URL</label>
                    <input type="text" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url', $member->linkedin_url) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
                </div>
            </div>
            <div>
                <label for="sort_order" class="block text-sm font-medium text-charcoal-700">Sort order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $member->sort_order ?? 0) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_board" id="is_board" value="1" class="rounded border-charcoal-300 text-forest-700 focus:ring-forest-500" @checked(old('is_board', $member->is_board))>
                <label for="is_board" class="text-sm text-charcoal-700">Board member</label>
            </div>

            @include('admin.partials.media-picker', [
                'name' => 'photo_id',
                'value' => $member->photo_id,
                'label' => 'Team photo',
                'help' => 'Portrait photo for the leadership / team page.',
            ])
        </div>
    </form>
@endsection
