<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\FormDefinition;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventRegistrationSubmitted extends Mailable
{
    use SerializesModels;

    public function __construct(
        public Event $event,
        public EventRegistration $registration,
    ) {}

    public function envelope(): Envelope
    {
        $from = new Address(
            (string) config('mail.from.address'),
            (string) config('mail.from.name'),
        );

        return new Envelope(
            from: $from,
            subject: "New event registration: {$this->event->title}",
            to: array_map(
                fn (string $email) => new Address($email),
                FormDefinition::mergeNotifyEmails([]),
            ),
            replyTo: $this->registration->email
                ? [new Address($this->registration->email, $this->registration->name ?: $this->registration->email)]
                : [$from],
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.event-registration-submitted',
        );
    }
}
