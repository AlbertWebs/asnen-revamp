<?php

namespace App\Console\Commands;

use App\Mail\MailerTestMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestMail extends Command
{
    protected $signature = 'mail:test {to : Destination email address}';

    protected $description = 'Send a test message through the configured mailer (Amazon SES in production)';

    public function handle(): int
    {
        $to = (string) $this->argument('to');

        if (! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $this->error('Provide a valid email address.');

            return self::FAILURE;
        }

        $from = (string) config('mail.from.address');
        $mailer = (string) config('mail.default');

        Mail::to($to)->send(new MailerTestMessage($mailer));

        $this->info("Sent a test email to {$to} from {$from} via {$mailer}.");

        return self::SUCCESS;
    }
}
