<?php

namespace App\Console\Commands;

use App\Models\FormSubmission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AnonymizeOldSubmissions extends Command
{
    protected $signature = 'submissions:anonymize {--days=365 : Retention period in days before anonymization}';

    protected $description = 'Anonymize form submissions older than the retention period (Phase 5 data retention)';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoff = now()->subDays($days);

        $query = FormSubmission::query()
            ->withTrashed()
            ->where('created_at', '<', $cutoff)
            ->where(function ($q) {
                $q->whereNotNull('ip')
                    ->orWhereNotNull('user_agent')
                    ->orWhereRaw("json_extract(data, '$.email') IS NOT NULL");
            });

        $count = $query->count();

        if ($count === 0) {
            $this->info('No submissions require anonymization.');

            return self::SUCCESS;
        }

        if (! $this->confirm("Anonymize {$count} submission(s) older than {$days} days?", true)) {
            return self::SUCCESS;
        }

        $anonymized = 0;

        $query->chunkById(100, function ($submissions) use (&$anonymized) {
            DB::transaction(function () use ($submissions, &$anonymized) {
                foreach ($submissions as $submission) {
                    $data = $submission->data ?? [];
                    $anonymizedData = [];

                    foreach ($data as $key => $value) {
                        if (in_array($key, ['email', 'phone', 'name', 'contact_name', 'organisation'], true)) {
                            $anonymizedData[$key] = '[redacted]';
                        } else {
                            $anonymizedData[$key] = is_string($value) ? '[redacted]' : $value;
                        }
                    }

                    $submission->update([
                        'data' => $anonymizedData,
                        'ip' => null,
                        'user_agent' => null,
                        'admin_notes' => trim(($submission->admin_notes ?? '')."\n[Anonymized ".now()->toDateTimeString().']'),
                    ]);

                    $anonymized++;
                }
            });
        });

        $this->info("Anonymized {$anonymized} submission(s).");

        return self::SUCCESS;
    }
}
