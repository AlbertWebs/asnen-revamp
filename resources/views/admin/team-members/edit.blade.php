@extends('layouts.admin')

@section('title', $member->exists ? 'Edit' : 'New')
@section('heading', $member->exists ? 'Edit' : 'New')

@section('content')
    <form method="POST" action="{{ $member->exists ? route('admin.team-members.update', $member) : route('admin.team-members.store') }}">
        @csrf
        @if ($member->exists) @method('PUT') @endif

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            @include('admin.partials.publish-buttons', ['model' => $member, 'routePrefix' => 'team-members'])
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <label for="name" class="mt-4 block text-sm font-medium text-charcoal-700">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $member->name) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="slug" class="mt-4 block text-sm font-medium text-charcoal-700">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $member->slug) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="title_role" class="mt-4 block text-sm font-medium text-charcoal-700">Title Role</label>
            <input type="text" name="title_role" id="title_role" value="{{ old('title_role', $member->title_role) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="bio" class="mt-4 block text-sm font-medium text-charcoal-700">Bio</label>
            <textarea name="bio" id="bio" rows="4" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('bio', $member->bio) }}</textarea>
            <label for="email" class="mt-4 block text-sm font-medium text-charcoal-700">Email</label>
            <input type="text" name="email" id="email" value="{{ old('email', $member->email) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="linkedin_url" class="mt-4 block text-sm font-medium text-charcoal-700">Linkedin Url</label>
            <input type="text" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url', $member->linkedin_url) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="sort_order" class="mt-4 block text-sm font-medium text-charcoal-700">Sort Order</label>
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $member->sort_order) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">

            @include('admin.partials.media-picker', [
                'name' => 'photo_id',
                'value' => $member->photo_id,
                'label' => 'Team photo',
                'help' => 'Portrait photo for the leadership / team page.',
            ])
        </div>
    </form>
@endsection