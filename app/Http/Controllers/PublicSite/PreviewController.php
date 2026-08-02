<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\HtmlSanitizer;
use Illuminate\Http\Request;

class PreviewController extends Controller
{
    public function __construct(private HtmlSanitizer $sanitizer) {}

    public function __invoke(Request $request, Page $page)
    {
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        $page->load(['blocks' => fn ($q) => $q->where('is_visible', true)]);

        return view('public.preview', array_merge(compact('page'), ['sanitizer' => $this->sanitizer]));
    }
}
