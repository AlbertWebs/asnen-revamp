<?php

namespace App\Services;

use App\Enums\FormSubmissionStatus;
use App\Mail\AdminFormSubmitted;
use App\Models\FormDefinition;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
            $recipients = $form->notify_emails ?? [];

            if ($recipients !== []) {
                Mail::queue(new AdminFormSubmitted($submission));
            }
        }

        return $submission;
    }
}
