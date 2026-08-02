<?php

namespace Tests\Feature\Forms;

use App\Enums\FormSubmissionStatus;
use App\Models\FormDefinition;
use App\Models\FormSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        FormDefinition::create([
            'name' => 'Contact',
            'slug' => 'contact',
            'type' => 'contact',
            'fields' => [],
            'success_message' => 'Thank you.',
            'notify_emails' => ['admin@example.com'],
            'is_active' => true,
        ]);
    }

    public function test_valid_contact_form_submits_successfully(): void
    {
        $response = $this->post(route('site.contact.store'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '+254700000000',
            'subject' => 'Program inquiry',
            'message' => 'I would like to learn more about inclusive education programs.',
        ]);

        $response->assertRedirect();

        $submission = FormSubmission::first();
        $this->assertNotNull($submission);
        $this->assertSame(FormSubmissionStatus::New, $submission->status);
        $this->assertFalse($submission->honeypot_caught);
        $this->assertSame('Jane Doe', $submission->data['name']);
    }

    public function test_honeypot_field_rejects_submission(): void
    {
        $response = $this->post(route('site.contact.store'), [
            'name' => 'Spammer',
            'email' => 'spam@example.com',
            'subject' => 'Buy now',
            'message' => 'Spam message',
            'website' => 'http://spam.example',
        ]);

        $response->assertSessionHasErrors('website');
        $this->assertDatabaseCount('form_submissions', 0);
    }

    public function test_contact_form_validation_errors_on_missing_fields(): void
    {
        $response = $this->post(route('site.contact.store'), []);

        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
        $this->assertDatabaseCount('form_submissions', 0);
    }
}
