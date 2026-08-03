@extends('layouts.admin')

@section('title', $faq->exists ? 'Edit' : 'New')
@section('heading', $faq->exists ? 'Edit' : 'New')

@section('content')
    @if ($faq->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $faq, 'routePrefix' => 'faqs'])
        </div>
    @endif

    <form method="POST" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}">
        @csrf
        @if ($faq->exists) @method('PUT') @endif

        <div class="mb-4 flex justify-end">
            <button type="submit" class="rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save</button>
        </div>

        <div class="max-w-3xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
            <label for="question" class="mt-4 block text-sm font-medium text-charcoal-700">Question</label>
            <input type="text" name="question" id="question" value="{{ old('question', $faq->question) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="answer" class="mt-4 block text-sm font-medium text-charcoal-700">Answer</label>
            <textarea name="answer" id="answer" rows="4" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">{{ old('answer', $faq->answer) }}</textarea>
            <label for="category" class="mt-4 block text-sm font-medium text-charcoal-700">Category</label>
            <input type="text" name="category" id="category" value="{{ old('category', $faq->category) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
            <label for="sort_order" class="mt-4 block text-sm font-medium text-charcoal-700">Sort Order</label>
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $faq->sort_order) }}" class="mt-1 block w-full rounded-md border-charcoal-300 shadow-sm focus:border-forest-500 focus:ring-forest-500">
        </div>
    </form>
@endsection