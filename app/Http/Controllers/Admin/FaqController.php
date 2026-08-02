<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->can('faqs.view'), 403);

        $faqs = Faq::query()->orderBy('sort_order')->paginate(30);

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->can('faqs.create'), 403);

        return view('admin.faqs.edit', ['faq' => new Faq(['status' => PublishStatus::Draft])]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('faqs.create'), 403);

        $faq = Faq::create($this->validateFaq($request));

        return redirect()->route('admin.faqs.edit', $faq)->with('success', 'FAQ created.');
    }

    public function edit(Faq $faq): View
    {
        abort_unless(auth()->user()?->can('faqs.update'), 403);

        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        abort_unless(auth()->user()?->can('faqs.update'), 403);

        $faq->update($this->validateFaq($request));

        return back()->with('success', 'FAQ updated.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        abort_unless(auth()->user()?->can('faqs.delete'), 403);

        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted.');
    }

    public function publish(Faq $faq): RedirectResponse
    {
        abort_unless(auth()->user()?->can('faqs.publish'), 403);

        $faq->publish();

        return back()->with('success', 'FAQ published.');
    }

    public function unpublish(Faq $faq): RedirectResponse
    {
        abort_unless(auth()->user()?->can('faqs.publish'), 403);

        $faq->unpublish();

        return back()->with('success', 'FAQ unpublished.');
    }

    private function validateFaq(Request $request): array
    {
        return $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'answer' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
