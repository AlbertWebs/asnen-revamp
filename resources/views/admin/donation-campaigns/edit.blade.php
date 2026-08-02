@extends('layouts.admin')

@section('title', $campaign->exists ? 'Edit' : 'New')
@section('heading', $campaign->exists ? 'Edit' : 'New')

@section('content')
    <form method="POST" action="{{ $campaign->exists ? route('admin.donation-campaigns.update', $campaign) : route('admin.donation-campaigns.store') }}">
        @csrf
        @if ($campaign->exists) @method('PUT') @endif

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            @include('admin.partials.publish-buttons', ['model' => $campaign, 'routePrefix' => 'donation-campaigns'])
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <label for="title" class="mt-4 block text-sm font-medium text-charcoal-700">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $campaign->title) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="slug" class="mt-4 block text-sm font-medium text-charcoal-700">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $campaign->slug) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="summary" class="mt-4 block text-sm font-medium text-charcoal-700">Summary</label>
            <textarea name="summary" id="summary" rows="4" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('summary', $campaign->summary) }}</textarea>
            <label for="body" class="mt-4 block text-sm font-medium text-charcoal-700">Body</label>
            <textarea name="body" id="body" rows="4" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('body', $campaign->body) }}</textarea>
            <label for="goal_amount" class="mt-4 block text-sm font-medium text-charcoal-700">Goal Amount</label>
            <input type="number" name="goal_amount" id="goal_amount" value="{{ old('goal_amount', $campaign->goal_amount) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="currency" class="mt-4 block text-sm font-medium text-charcoal-700">Currency</label>
            <input type="text" name="currency" id="currency" value="{{ old('currency', $campaign->currency) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
        </div>
    </form>
@endsection