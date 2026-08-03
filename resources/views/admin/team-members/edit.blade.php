@extends('layouts.admin')

@section('title', $member->exists ? 'Edit Team Member' : 'New Team Member')
@section('heading', $member->exists ? 'Edit Team Member' : 'New Team Member')

@section('content')
    @if ($member->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $member, 'routePrefix' => 'team-members'])
        </div>
    @endif

    <form method="POST" action="{{ $member->exists ? route('admin.team-members.update', $member) : route('admin.team-members.store') }}" class="admin-form admin-form--wide">
        @csrf
        @if ($member->exists) @method('PUT') @endif

        <div class="admin-form__body">
            <header class="admin-form__header">
                <p class="admin-form__eyebrow">Team members</p>
                <h2 class="admin-form__title">{{ $member->exists ? 'Edit team member' : 'New team member' }}</h2>
            </header>

            <div class="admin-form__section">
                <div class="admin-field">
                    <label for="name" class="admin-label">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $member->name) }}" required class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="slug" class="admin-label">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $member->slug) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="title_role" class="admin-label">Role / title</label>
                    <input type="text" name="title_role" id="title_role" value="{{ old('title_role', $member->title_role) }}" required class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="bio" class="admin-label">Bio</label>
                    <textarea name="bio" id="bio" rows="5" class="admin-textarea">{{ old('bio', $member->bio) }}</textarea>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="admin-field">
                        <label for="email" class="admin-label">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $member->email) }}" class="admin-input">
                    </div>
                    <div class="admin-field">
                        <label for="linkedin_url" class="admin-label">LinkedIn URL</label>
                        <input type="text" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url', $member->linkedin_url) }}" class="admin-input">
                    </div>
                </div>
                <div class="admin-field">
                    <label for="sort_order" class="admin-label">Sort order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $member->sort_order ?? 0) }}" class="admin-input">
                </div>
                <label class="admin-check">
                    <input type="checkbox" name="is_board" id="is_board" value="1" @checked(old('is_board', $member->is_board))>
                    <span>Board member</span>
                </label>

                @include('admin.partials.media-picker', [
                    'name' => 'photo_id',
                    'value' => $member->photo_id,
                    'label' => 'Team photo',
                    'help' => 'Portrait photo for the leadership / team page.',
                ])
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary">Save</button>
            </div>
        </div>
    </form>
@endsection
