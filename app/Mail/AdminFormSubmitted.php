<?php

namespace App\Mail;

use App\Models\FormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminFormSubmitted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public FormSubmission $submission) {}

    public function envelope(): Envelope
    {
        $formName = $this->submission->formDefinition?->name ?? 'Form';
        $recipients = $this->submission->formDefinition?->notify_emails ?? [];

        return new Envelope(
            subject: "New {$formName} submission",
            to: array_map(fn (string $email) => new Address($email), $recipients),
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.admin-form-submitted',
        );
    }
}
