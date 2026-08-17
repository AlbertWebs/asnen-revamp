<?php

namespace App\Services;

use App\Enums\FormSubmissionStatus;
use App\Mail\AdminFormSubmitted;
use App\Models\FormDefinition;
use App\Models\FormSubmission;
use App\Models\MailLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class FormSubmissionService
{
    public function store(FormDefinition $form, array $data, Request $request): FormSubmission
    {
        $honeypotCaught = $request->filled('website');

        $submission = FormSubmission::create([
            'form_definition_id' => $form->id,
            'data' => $data,
            'status' => $honeypotCaught ? FormSubmissionStatus::Spam : FormSubmissionStatus::New,
            'honeypot_caught' => $honeypotCaught,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'consent_at' => now(),
            'confirmation_token' => Str::random(64),
        ]);

        if (! $honeypotCaught) {
            $submission->load('formDefinition');
            $this->sendStaffNotification($submission);
        }

        return $submission;
    }

    private function sendStaffNotification(FormSubmission $submission): void
    {
        try {
            Mail::send(new AdminFormSubmitted($submission));
        } catch (Throwable $e) {
            MailLog::failLatest($e->getMessage());
            Log::error('Failed to send form notification email.', [
                'submission_id' => $submission->id,
                'form' => $submission->formDefinition?->slug,
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
