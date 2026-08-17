<?php

namespace App\Mail;

use App\Models\FormDefinition;
use App\Models\FormSubmission;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminFormSubmitted extends Mailable
{
    use SerializesModels;

    public function __construct(public FormSubmission $submission) {}

    public function envelope(): Envelope
    {
        $formName = $this->submission->formDefinition?->name ?? 'Form';
        $recipients = $this->submission->formDefinition?->notificationRecipients()
            ?? FormDefinition::mergeNotifyEmails([]);

        return new Envelope(
            from: $this->fromAddress(),
            subject: "New {$formName} submission",
            to: array_map(fn (string $email) => new Address($email), $recipients),
            replyTo: $this->submitterReplyTo() ?: [$this->fromAddress()],
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.admin-form-submitted',
        );
    }

    private function fromAddress(): Address
    {
        return new Address(
            (string) config('mail.from.address'),
            (string) config('mail.from.name'),
        );
    }

    /**
     * @return list<Address>
     */
    private function submitterReplyTo(): array
    {
        $email = $this->submission->contactEmail();

        if (! is_string($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [];
        }

        $name = $this->submission->contactName();

        return [
            new Address(
                $email,
                is_string($name) && $name !== '' ? $name : $email,
            ),
        ];
    }
}
