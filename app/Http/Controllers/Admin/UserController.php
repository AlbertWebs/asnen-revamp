<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->can('users.view'), 403);

        $users = User::query()->with('roles')->latest()->paginate(25);

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user): View
    {
        abort_unless(auth()->user()?->can('users.manage'), 403);

        $roles = Role::query()->orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        abort_unless(auth()->user()?->can('users.manage'), 403);

        $validated = $request->validate([
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $user->syncRoles($validated['roles'] ?? []);

        return back()->with('success', 'User roles updated.');
    }
}
