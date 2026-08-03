@extends('layouts.admin')

@section('title', 'Manage Roles')
@section('heading', 'Manage Roles: '.$user->name)

@section('content')
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="admin-form">
        @csrf
        @method('PUT')

        <div class="admin-form__body">
            <header class="admin-form__header">
                <p class="admin-form__eyebrow">Users</p>
                <h2 class="admin-form__title">{{ $user->name }}</h2>
                <p class="admin-form__intro">{{ $user->email }}</p>
            </header>

            <div class="admin-form__section">
                <fieldset class="space-y-2.5">
                    <legend class="admin-form__section-title">Assign roles</legend>
                    @foreach ($roles as $role)
                        <label class="admin-check">
                            <input
                                type="checkbox"
                                name="roles[]"
                                value="{{ $role->name }}"
                                @checked($user->roles->contains('name', $role->name))
                            >
                            <span>{{ $role->name }}</span>
                        </label>
                    @endforeach
                </fieldset>
            </div>

            <div class="admin-form__actions">
                <button type="submit" class="admin-btn-primary">Save roles</button>
            </div>
        </div>
    </form>
@endsection
