@extends('layouts.admin')

@section('title', $faq->exists ? 'Edit' : 'New')
@section('heading', $faq->exists ? 'Edit' : 'New')

@section('content')
    @if ($faq->exists)
        <div class="mb-4 flex flex-wrap items-center gap-3">
            @include('admin.partials.publish-buttons', ['model' => $faq, 'routePrefix' => 'faqs'])
        </div>
    @endif

    <form method="POST" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" class="admin-form admin-form--wide">
        @csrf
        @if ($faq->exists) @method('PUT') @endif

        <div class="admin-form__body">
            <header class="admin-form__header">
                <p class="admin-form__eyebrow">FAQs</p>
                <h2 class="admin-form__title">{{ $faq->exists ? 'Edit FAQ' : 'New FAQ' }}</h2>
            </header>

            <div class="admin-form__section">
                <div class="admin-field">
                    <label for="question" class="admin-label">Question</label>
                    <input type="text" name="question" id="question" value="{{ old('question', $faq->question) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="answer" class="admin-label">Answer</label>
                    <textarea name="answer" id="answer" rows="4" class="admin-textarea">{{ old('answer', $faq->answer) }}</textarea>
                </div>
                <div class="admin-field">
                    <label for="category" class="admin-label">Category</label>
                    <input type="text" name="category" id="category" value="{{ old('category', $faq->category) }}" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="sort_order" class="admin-label">Sort order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $faq->sort_order) }}" class="admin-input">
                </div>
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary">Save</button>
            </div>
        </div>
    </form>
@endsection
