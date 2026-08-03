<?php

namespace Tests\Feature\Forms;

use App\Enums\FormSubmissionStatus;
use App\Models\FormDefinition;
use App\Models\FormSubmission;
use App\Services\MathCaptcha;
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

    public function test_valid_contact_form_submits_successfully(): void
    {
        $response = $this->post(route('site.contact.store'), array_merge([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '+254700000000',
            'subject' => 'Program inquiry',
            'message' => 'I would like to learn more about inclusive education programs.',
        ], $this->captchaPayload()));

        $response->assertRedirect();

        $submission = FormSubmission::first();
        $this->assertNotNull($submission);
        $this->assertSame(FormSubmissionStatus::New, $submission->status);
        $this->assertFalse($submission->honeypot_caught);
        $this->assertSame('Jane Doe', $submission->data['name']);
        $this->assertArrayNotHasKey('math_token', $submission->data);
    }

    public function test_ajax_contact_form_returns_json_success(): void
    {
        $response = $this->postJson(route('site.contact.store'), array_merge([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'subject' => 'Program inquiry',
            'message' => 'I would like to learn more about inclusive education programs.',
        ], $this->captchaPayload()));

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Thank you.',
            ])
            ->assertJsonStructure(['redirect']);
    }

    public function test_wrong_math_answer_is_rejected(): void
    {
        $response = $this->post(route('site.contact.store'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'subject' => 'Program inquiry',
            'message' => 'Hello there.',
            'math_token' => app(MathCaptcha::class)->tokenForAnswer(9),
            'math_answer' => 3,
        ]);

        $response->assertSessionHasErrors('math_answer');
        $this->assertDatabaseCount('form_submissions', 0);
    }

    public function test_honeypot_field_rejects_submission(): void
    {
        $response = $this->post(route('site.contact.store'), array_merge([
            'name' => 'Spammer',
            'email' => 'spam@example.com',
            'subject' => 'Buy now',
            'message' => 'Spam message',
            'website' => 'http://spam.example',
        ], $this->captchaPayload()));

        $response->assertSessionHasErrors('website');
        $this->assertDatabaseCount('form_submissions', 0);
    }

    public function test_contact_form_validation_errors_on_missing_fields(): void
    {
        $response = $this->post(route('site.contact.store'), $this->captchaPayload());

        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
        $this->assertDatabaseCount('form_submissions', 0);
    }

    public function test_math_challenge_endpoint_returns_question_and_token(): void
    {
        $response = $this->getJson(route('site.forms.math-challenge'));

        $response->assertOk()
            ->assertJsonStructure(['question', 'token']);
        $this->assertArrayNotHasKey('answer', $response->json());
    }
}
