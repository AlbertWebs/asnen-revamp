@extends('layouts.admin')

@section('title', $redirect->exists ? 'Edit Redirect' : 'New Redirect')
@section('heading', $redirect->exists ? 'Edit Redirect' : 'New Redirect')

@section('content')
    <form method="POST" action="{{ $redirect->exists ? route('admin.redirects.update', $redirect) : route('admin.redirects.store') }}" class="admin-form admin-form--wide">
        @csrf
        @if ($redirect->exists) @method('PUT') @endif

        <div class="admin-form__body">
            <header class="admin-form__header">
                <p class="admin-form__eyebrow">Redirects</p>
                <h2 class="admin-form__title">{{ $redirect->exists ? 'Edit redirect' : 'New redirect' }}</h2>
            </header>

            <div class="admin-form__section">
                <div class="admin-field">
                    <label for="from_path" class="admin-label">From path</label>
                    <input type="text" name="from_path" id="from_path" value="{{ old('from_path', $redirect->from_path) }}" required placeholder="/old-page.html" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="to_path" class="admin-label">To path</label>
                    <input type="text" name="to_path" id="to_path" value="{{ old('to_path', $redirect->to_path) }}" required placeholder="/new-page" class="admin-input">
                </div>
                <div class="admin-field">
                    <label for="status_code" class="admin-label">Status code</label>
                    <select name="status_code" id="status_code" class="admin-select">
                        @foreach ([301, 302, 307, 308] as $code)
                            <option value="{{ $code }}" @selected((int) old('status_code', $redirect->status_code ?: 301) === $code)>{{ $code }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="admin-check">
                    <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $redirect->is_active ?? true))>
                    <span>Active</span>
                </label>
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary">Save</button>
            </div>
        </div>
    </form>
@endsection
