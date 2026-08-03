@extends('layouts.admin')

@section('title', $partner->exists ? 'Edit' : 'New')
@section('heading', $partner->exists ? 'Edit' : 'New')

@section('content')
    @if ($partner->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $partner, 'routePrefix' => 'partners'])
        </div>
    @endif

    <form method="POST" action="{{ $partner->exists ? route('admin.partners.update', $partner) : route('admin.partners.store') }}" class="admin-form admin-form--wide">
        @csrf
        @if ($partner->exists) @method('PUT') @endif

        <div class="admin-form__body">
            <header class="admin-form__header">
                <p class="admin-form__eyebrow">Partners</p>
                <h2 class="admin-form__title">{{ $partner->exists ? 'Edit partner' : 'New partner' }}</h2>
            </header>

            <div class="admin-form__section">
                <div class="admin-field">
                    <label for="name" class="admin-label">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $partner->name) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="slug" class="admin-label">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $partner->slug) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="description" class="admin-label">Description</label>
                    <textarea name="description" id="description" rows="4" class="admin-textarea">{{ old('description', $partner->description) }}</textarea>
                </div>
                <div class="admin-field">
                    <label for="url" class="admin-label">URL</label>
                    <input type="text" name="url" id="url" value="{{ old('url', $partner->url) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="category" class="admin-label">Category</label>
                    <input type="text" name="category" id="category" value="{{ old('category', $partner->category) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="sort_order" class="admin-label">Sort order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $partner->sort_order) }}" class="admin-input">
                </div>

                @include('admin.partials.media-picker', [
                    'name' => 'logo_id',
                    'value' => $partner->logo_id,
                    'label' => 'Partner logo',
                    'help' => 'Square or landscape logos work best.',
                ])
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary">Save</button>
            </div>
        </div>
    </form>
@endsection
