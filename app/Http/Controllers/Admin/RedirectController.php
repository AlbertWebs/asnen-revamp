<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RedirectController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->can('redirects.view'), 403);

        $redirects = Redirect::query()->orderBy('from_path')->paginate(30);

        return view('admin.redirects.index', compact('redirects'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->can('redirects.create'), 403);

        return view('admin.redirects.edit', ['redirect' => new Redirect(['status_code' => 301, 'is_active' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('redirects.create'), 403);

        $redirect = Redirect::create($this->validateRedirect($request));

        return redirect()->route('admin.redirects.edit', $redirect)->with('success', 'Redirect created.');
    }

    public function edit(Redirect $redirect): View
    {
        abort_unless(auth()->user()?->can('redirects.update'), 403);

        return view('admin.redirects.edit', compact('redirect'));
    }

    public function update(Request $request, Redirect $redirect): RedirectResponse
    {
        abort_unless(auth()->user()?->can('redirects.update'), 403);

        $redirect->update($this->validateRedirect($request, $redirect));

        return back()->with('success', 'Redirect updated.');
    }

    public function destroy(Redirect $redirect): RedirectResponse
    {
        abort_unless(auth()->user()?->can('redirects.delete'), 403);

        $redirect->delete();

        return redirect()->route('admin.redirects.index')->with('success', 'Redirect deleted.');
    }

    private function validateRedirect(Request $request, ?Redirect $redirect = null): array
    {
        return $request->validate([
            'from_path' => ['required', 'string', 'max:255', 'unique:redirects,from_path,'.($redirect?->id ?? 'NULL')],
            'to_path' => ['required', 'string', 'max:500'],
            'status_code' => ['nullable', 'integer', 'in:301,302,307,308'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
