<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventRegistrantMessage extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Event $event,
        public EventRegistration $registration,
        public string $subjectLine,
        public string $body,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: $this->fromAddress(),
            replyTo: [$this->fromAddress()],
            subject: $this->personalize($this->subjectLine),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event-registrant-message',
            with: [
                'name' => $this->registration->name,
                'eventTitle' => $this->event->title,
                'body' => $this->personalize($this->body),
            ],
        );
    }

    private function fromAddress(): Address
    {
        return new Address(
            (string) config('mail.from.address'),
            (string) config('mail.from.name'),
        );
    }

    private function personalize(string $text): string
    {
        return str_replace(
            ['{name}', '{event}'],
            [$this->registration->name, $this->event->title],
            $text
        );
    }
}
