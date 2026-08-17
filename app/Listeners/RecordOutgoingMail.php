<?php

namespace App\Listeners;

use App\Enums\MailLogStatus;
use App\Models\MailLog;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Throwable;

class RecordOutgoingMail
{
    public const HEADER = 'X-Asnen-Mail-Log-Id';

    public function sending(MessageSending $event): void
    {
        try {
            $message = $event->message;
            $from = $this->firstAddress($message->getFrom());

            $log = MailLog::create([
                'mailer' => config('mail.default'),
                'mailable' => $this->mailableName($event),
                'from_address' => $from['address'],
                'from_name' => $from['name'],
                'to_addresses' => $this->addressList($message->getTo()),
                'cc_addresses' => $this->addressList($message->getCc()),
                'bcc_addresses' => $this->addressList($message->getBcc()),
                'reply_to_addresses' => $this->addressList($message->getReplyTo()),
                'subject' => $message->getSubject(),
                'status' => MailLogStatus::Sending,
            ]);

            $message->getHeaders()->addTextHeader(self::HEADER, (string) $log->id);
        } catch (Throwable $e) {
            report($e);
        }
    }

    public function sent(MessageSent $event): void
    {
        try {
            $log = $this->logFromMessage($event->message);
            $messageId = $event->message->getHeaders()->get('Message-ID')?->getBodyAsString();
            $log?->markSent($messageId);
        } catch (Throwable $e) {
            report($e);
        }
    }

    private function logFromMessage(Email $message): ?MailLog
    {
        $header = $message->getHeaders()->get(self::HEADER);
        $id = $header?->getBodyAsString();

        if (! is_numeric($id)) {
            return null;
        }

        return MailLog::query()->find((int) $id);
    }

    private function mailableName(MessageSending $event): ?string
    {
        $data = $event->data ?? [];
        $mailable = $data['__laravel_mailable'] ?? $data['__laravel_notification'] ?? null;

        if (is_string($mailable) && $mailable !== '') {
            return class_basename($mailable);
        }

        if (is_object($mailable)) {
            return class_basename($mailable);
        }

        return null;
    }

    /**
     * @param  list<Address>|null  $addresses
     * @return list<string>
     */
    private function addressList(?array $addresses): array
    {
        return collect($addresses ?? [])
            ->map(fn (Address $address) => $address->getAddress())
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @param  list<Address>|null  $addresses
     * @return array{address: ?string, name: ?string}
     */
    private function firstAddress(?array $addresses): array
    {
        $address = collect($addresses ?? [])->first();

        return [
            'address' => $address?->getAddress(),
            'name' => $address?->getName() ?: null,
        ];
    }
}
