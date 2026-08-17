<?php

namespace Tests\Feature\Forms;

use App\Enums\FormSubmissionStatus;
use App\Mail\AdminFormSubmitted;
use App\Models\FormDefinition;
use App\Models\FormSubmission;
use App\Services\MathCaptcha;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PublicFormNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    /**
     * @return array{math_token: string, math_answer: int}
     */
    private function captchaPayload(int $answer = 7): array
    {
        return [
            'math_token' => app(MathCaptcha::class)->tokenForAnswer($answer),
            'math_answer' => $answer,
        ];
    }

    private function form(string $slug, string $type, string $name): FormDefinition
    {
        return FormDefinition::create([
            'name' => $name,
            'slug' => $slug,
            'type' => $type,
            'fields' => [],
            'success_message' => 'Thank you.',
            'notify_emails' => [],
            'is_active' => true,
        ]);
    }

    public function test_membership_form_is_stored_and_notifies_info(): void
    {
        $this->form('membership', 'membership', 'Membership Application');

        $payload = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '+254700000000',
            'membership_type' => 'individual',
            'motivation' => 'I want to support inclusive education.',
        ];

        $this->post(route('site.get-involved.membership.store'), array_merge($payload, $this->captchaPayload()))
            ->assertRedirect();

        $this->assertStoredAndNotified($payload);
    }

    public function test_volunteer_form_is_stored_and_notifies_info(): void
    {
        $this->form('volunteer', 'volunteer', 'Volunteer Application');

        $payload = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '+254700000000',
            'skills' => 'Teaching support',
            'availability' => 'Weekends',
        ];

        $this->post(route('site.get-involved.volunteer.store'), array_merge($payload, $this->captchaPayload()))
            ->assertRedirect();

        $this->assertStoredAndNotified($payload);
    }

    public function test_partner_form_is_stored_and_notifies_info(): void
    {
        $this->form('partner', 'partnership', 'Partnership Inquiry');

        $payload = [
            'organisation' => 'Example Org',
            'contact_name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '+254700000000',
            'proposal' => 'We would like to collaborate on assessment camps.',
        ];

        $this->post(route('site.get-involved.partner.store'), array_merge($payload, $this->captchaPayload()))
            ->assertRedirect();

        $this->assertStoredAndNotified($payload);
    }

    public function test_donate_form_is_stored_and_notifies_info(): void
    {
        $this->form('donate', 'donation', 'Donate / Program Support');

        $payload = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'program_interest' => 'general',
            'message' => 'I would like to support ASNEN.',
        ];

        $this->post(route('site.get-involved.donate.store'), array_merge($payload, $this->captchaPayload()))
            ->assertRedirect();

        $this->assertStoredAndNotified($payload);
    }

    public function test_newsletter_form_is_stored_and_notifies_info(): void
    {
        $this->form('newsletter', 'newsletter', 'Newsletter');

        $payload = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'consent' => '1',
        ];

        $this->post(route('site.newsletter.subscribe'), array_merge($payload, $this->captchaPayload()))
            ->assertRedirect();

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'jane@example.com',
            'status' => 'subscribed',
        ]);

        $submission = FormSubmission::first();
        $this->assertNotNull($submission);
        $this->assertSame('Jane Doe', $submission->data['name']);
        $this->assertSame('jane@example.com', $submission->data['email']);

        Mail::assertSent(AdminFormSubmitted::class, function (AdminFormSubmitted $mail) {
            return $mail->hasTo('info@asnenafrica.org');
        });
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function assertStoredAndNotified(array $payload): void
    {
        $submission = FormSubmission::query()->latest('id')->first();
        $this->assertNotNull($submission);
        $this->assertSame(FormSubmissionStatus::New, $submission->status);
        $this->assertFalse($submission->honeypot_caught);

        foreach ($payload as $key => $value) {
            $this->assertSame($value, $submission->data[$key] ?? null, "Missing stored field [{$key}]");
        }

        Mail::assertSent(AdminFormSubmitted::class, function (AdminFormSubmitted $mail) {
            return $mail->hasTo('info@asnenafrica.org');
        });
    }
}
