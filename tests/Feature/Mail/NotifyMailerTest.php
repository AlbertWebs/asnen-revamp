<?php

namespace Tests\Feature\Mail;

use App\Console\Commands\SendTestMail;
use App\Enums\FormSubmissionStatus;
use App\Mail\AdminFormSubmitted;
use App\Mail\EventRegistrantMessage;
use App\Mail\MailerTestMessage;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\FormDefinition;
use App\Models\FormSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotifyMailerTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_messages_come_from_notify_address(): void
    {
        $event = Event::create([
            'title' => 'Inclusive Education Forum',
            'slug' => 'inclusive-education-forum',
            'starts_at' => now()->addWeek(),
        ]);
        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'name' => 'Amina Otieno',
            'email' => 'amina@example.com',
            'status' => 'confirmed',
        ]);

        $mailable = new EventRegistrantMessage(
            event: $event,
            registration: $registration,
            subjectLine: 'Hello {name}',
            body: 'See you at {event}.',
        );

        $envelope = $mailable->envelope();

        $this->assertSame('notify@asnenafrica.org', $envelope->from->address);
        $this->assertSame('notify@asnenafrica.org', $envelope->replyTo[0]->address);
        $this->assertSame('Hello Amina Otieno', $envelope->subject);
    }

    public function test_form_alerts_reply_to_the_submitter(): void
    {
        $form = FormDefinition::create([
            'name' => 'Contact',
            'slug' => 'contact',
            'type' => 'contact',
            'fields' => [],
            'success_message' => 'Thanks.',
            'notify_emails' => ['notify@asnenafrica.org'],
            'is_active' => true,
        ]);

        $submission = FormSubmission::create([
            'form_definition_id' => $form->id,
            'data' => [
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'message' => 'Hello',
            ],
            'status' => FormSubmissionStatus::New,
        ]);
        $submission->setRelation('formDefinition', $form);

        $envelope = (new AdminFormSubmitted($submission))->envelope();
        $recipients = collect($envelope->to)->map(fn (Address $address) => $address->address)->all();

        $this->assertSame('notify@asnenafrica.org', $envelope->from->address);
        $this->assertContains('info@asnenafrica.org', $recipients);
        $this->assertContains('notify@asnenafrica.org', $recipients);
        $this->assertSame('jane@example.com', $envelope->replyTo[0]->address);
    }

    public function test_mail_test_command_rejects_invalid_addresses(): void
    {
        $this->artisan(SendTestMail::class, ['to' => 'not-an-email'])
            ->assertFailed();
    }

    public function test_mail_test_command_sends_via_configured_mailer(): void
    {
        Mail::fake();

        $this->artisan(SendTestMail::class, ['to' => 'staff@example.com'])
            ->assertSuccessful();

        Mail::assertSent(MailerTestMessage::class, function (MailerTestMessage $mail) {
            return $mail->hasTo('staff@example.com')
                && $mail->hasFrom('notify@asnenafrica.org')
                && $mail->hasReplyTo('notify@asnenafrica.org');
        });
    }
}
