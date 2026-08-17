<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class MailerTestMessage extends Mailable
{
    public function __construct(public string $mailerName) {}

    public function envelope(): Envelope
    {
        $from = (string) config('mail.from.address');

        return new Envelope(
            from: new Address($from, (string) config('mail.from.name')),
            replyTo: [
                new Address(
                    (string) config('mail.reply_to.address'),
                    (string) config('mail.reply_to.name'),
                ),
            ],
            subject: "ASNEN mailer test ({$from})",
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: 'ASNEN mailer test from '.config('mail.from.address').' using the "'.$this->mailerName.'" mailer.',
        );
    }
}
