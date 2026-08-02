<?php

namespace App\Http\Controllers\Admin;

use App\Enums\FormSubmissionStatus;
use App\Enums\PublishStatus;
use App\Enums\SafeguardingStatus;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Event;
use App\Models\FormSubmission;
use App\Models\Gallery;
use App\Models\ImpactStory;
use App\Models\Page;
use App\Models\Program;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $publishableModels = [
            Page::class,
            Program::class,
            ImpactStory::class,
            Event::class,
            Article::class,
            Gallery::class,
        ];

        $draftCount = 0;
        $publishedCount = 0;

        foreach ($publishableModels as $model) {
            $draftCount += $model::query()->where('status', PublishStatus::Draft)->count();
            $publishedCount += $model::query()->where('status', PublishStatus::Published)->count();
        }

        $pendingSafeguarding = ImpactStory::query()
            ->where('safeguarding_status', SafeguardingStatus::Pending)
            ->count()
            + Page::query()
                ->where('requires_safeguarding', true)
                ->where('safeguarding_status', SafeguardingStatus::Pending)
                ->count()
            + Gallery::query()
                ->where('requires_safeguarding', true)
                ->where('safeguarding_status', SafeguardingStatus::Pending)
                ->count();

        $newSubmissions = FormSubmission::query()
            ->where('status', FormSubmissionStatus::New)
            ->count();

        return view('admin.dashboard', compact(
            'draftCount',
            'publishedCount',
            'pendingSafeguarding',
            'newSubmissions',
        ));
    }
}
