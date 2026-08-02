<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublishStatus;
use App\Http\Controllers\Admin\Concerns\NormalizesNullableIds;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    use NormalizesNullableIds;

    public function index(): View
    {
        abort_unless(auth()->user()?->can('articles.view'), 403);

        $articles = Article::query()->with('author')->latest()->paginate(20);

        return view('admin.articles.index', compact('articles'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->can('articles.create'), 403);

        return view('admin.articles.edit', ['article' => new Article(['status' => PublishStatus::Draft])]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('articles.create'), 403);

        $validated = $this->validateArticle($request);
        $validated['author_id'] = $request->user()->id;

        $article = Article::create($validated);

        return redirect()->route('admin.articles.edit', $article)->with('success', 'Article created.');
    }

    public function edit(Article $article): View
    {
        abort_unless(auth()->user()?->can('articles.update'), 403);

        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        abort_unless(auth()->user()?->can('articles.update'), 403);

        $article->update($this->validateArticle($request, $article));

        return back()->with('success', 'Article updated.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        abort_unless(auth()->user()?->can('articles.delete'), 403);

        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Article deleted.');
    }

    public function publish(Article $article): RedirectResponse
    {
        abort_unless(auth()->user()?->can('articles.publish'), 403);

        $article->publish();

        return back()->with('success', 'Article published.');
    }

    public function unpublish(Article $article): RedirectResponse
    {
        abort_unless(auth()->user()?->can('articles.publish'), 403);

        $article->unpublish();

        return back()->with('success', 'Article unpublished.');
    }

    private function validateArticle(Request $request, ?Article $article = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:articles,slug,'.($article?->id ?? 'NULL')],
            'excerpt' => ['nullable', 'string'],
            'body' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'reading_time_minutes' => ['nullable', 'integer', 'min:1'],
            'featured_image_id' => ['nullable', 'integer', 'exists:media_assets,id'],
        ]);
    }
}
