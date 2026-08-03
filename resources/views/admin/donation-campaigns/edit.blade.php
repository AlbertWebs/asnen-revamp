@extends('layouts.admin')

@section('title', $campaign->exists ? 'Edit' : 'New')
@section('heading', $campaign->exists ? 'Edit' : 'New')

@section('content')
    @if ($campaign->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $campaign, 'routePrefix' => 'donation-campaigns'])
        </div>
    @endif

    <form method="POST" action="{{ $campaign->exists ? route('admin.donation-campaigns.update', $campaign) : route('admin.donation-campaigns.store') }}" class="admin-form admin-form--wide">
        @csrf
        @if ($campaign->exists) @method('PUT') @endif

        <div class="admin-form__body">
            <header class="admin-form__header">
                <p class="admin-form__eyebrow">Donation campaigns</p>
                <h2 class="admin-form__title">{{ $campaign->exists ? 'Edit campaign' : 'New campaign' }}</h2>
            </header>

            <div class="admin-form__section">
                <div class="admin-field">
                    <label for="title" class="admin-label">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $campaign->title) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="slug" class="admin-label">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $campaign->slug) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="summary" class="admin-label">Summary</label>
                    <textarea name="summary" id="summary" rows="4" class="admin-textarea">{{ old('summary', $campaign->summary) }}</textarea>
                </div>
                <div class="admin-field">
                    <label for="body" class="admin-label">Body</label>
                    <textarea name="body" id="body" rows="4" class="admin-textarea">{{ old('body', $campaign->body) }}</textarea>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="admin-field">
                        <label for="goal_amount" class="admin-label">Goal amount</label>
                        <input type="number" name="goal_amount" id="goal_amount" value="{{ old('goal_amount', $campaign->goal_amount) }}" class="admin-input">
                    </div>
                    <div class="admin-field">
                        <label for="currency" class="admin-label">Currency</label>
                        <input type="text" name="currency" id="currency" value="{{ old('currency', $campaign->currency) }}" class="admin-input">
                    </div>
                </div>
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary">Save</button>
            </div>
        </div>
    </form>
@endsection
