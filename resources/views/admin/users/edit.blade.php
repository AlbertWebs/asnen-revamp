@extends('layouts.admin')

@section('title', 'Manage Roles')
@section('heading', 'Manage Roles: '.$user->name)

@section('content')
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="max-w-xl rounded-lg border border-charcoal-200 bg-white p-4 shadow-sm">
        @csrf
        @method('PUT')

        <p class="text-sm text-charcoal-600">{{ $user->email }}</p>

        <fieldset class="mt-4 space-y-2">
            <legend class="text-sm font-medium text-charcoal-700">Assign roles</legend>
            @foreach ($roles as $role)
                <label class="flex items-center gap-2 text-sm text-charcoal-700">
                    <input
                        type="checkbox"
                        name="roles[]"
                        value="{{ $role->name }}"
                        @checked($user->roles->contains('name', $role->name))
                        class="rounded border-charcoal-300 text-forest-600 focus:ring-forest-500"
                    >
                    {{ $role->name }}
                </label>
            @endforeach
        </fieldset>

        <button type="submit" class="mt-6 rounded-md bg-forest-700 px-4 py-2 text-sm font-medium text-white hover:bg-forest-800">Save roles</button>
    </form>
@endsection
