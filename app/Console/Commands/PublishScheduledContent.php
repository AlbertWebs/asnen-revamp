<?php

namespace App\Console\Commands;

use App\Enums\PublishStatus;
use App\Models\Article;
use App\Models\Event;
use App\Models\ImpactStory;
use App\Models\Page;
use App\Models\Program;
use App\Models\Publication;
use App\Models\Webinar;
use Illuminate\Console\Command;

class PublishScheduledContent extends Command
{
    protected $signature = 'content:publish-scheduled';

    protected $description = 'Promote scheduled content to published when scheduled_at has passed';

    /** @var list<class-string> */
    private array $models = [
        Page::class,
        Program::class,
        ImpactStory::class,
        Event::class,
        Webinar::class,
        Publication::class,
        Article::class,
    ];

    public function handle(): int
    {
        $published = 0;

        foreach ($this->models as $model) {
            if (! method_exists($model, 'scopeScheduledReady')) {
                continue;
            }

            $items = $model::query()->scheduledReady()->get();

            foreach ($items as $item) {
                if (method_exists($item, 'canPublishSafely') && ! $item->canPublishSafely()) {
                    $this->warn("Skipped {$model} #{$item->getKey()} — safeguarding gate.");

                    continue;
                }

                $item->update([
                    'status' => PublishStatus::Published,
                    'published_at' => $item->scheduled_at ?? now(),
                ]);
                $published++;
            }
        }

        $this->info("Published {$published} scheduled item(s).");

        return self::SUCCESS;
    }
}
