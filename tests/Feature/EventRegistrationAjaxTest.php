<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Mail\EventRegistrantMessage;
use App\Mail\EventRegistrationSubmitted;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use App\Services\MathCaptcha;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\Concerns\SeedsPublicSite;
use Tests\TestCase;

class EventRegistrationAjaxTest extends TestCase
{
    use RefreshDatabase;
    use SeedsPublicSite;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedPublicSiteSettings();
    }

    public function test_ajax_registration_stays_on_the_page_with_a_success_message(): void
    {
        $event = Event::create([
            'title' => 'NCPWD Webinar',
            'slug' => 'why-registration-matters-ncpwd',
            'starts_at' => now()->addMonth(),
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);

        $token = app(MathCaptcha::class)->tokenForAnswer(7);

        Mail::fake();

        $response = $this->postJson(route('site.events.register', $event->slug), [
            'name' => 'Amina Otieno',
            'email' => 'amina@example.com',
            'phone' => '+254700000000',
            'organization' => 'Nairobi School',
            'notes' => 'Need an interpreter.',
            'math_token' => $token,
            'math_answer' => 7,
        ]);

        $response->assertOk()
            ->assertJsonMissing(['redirect'])
            ->assertJsonFragment([
                'success' => true,
                'message' => 'Your registration is confirmed. We will email you with joining details.',
            ]);

        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'email' => 'amina@example.com',
            'name' => 'Amina Otieno',
            'phone' => '+254700000000',
            'organization' => 'Nairobi School',
            'notes' => 'Need an interpreter.',
        ]);

        Mail::assertSent(EventRegistrationSubmitted::class, function (EventRegistrationSubmitted $mail) {
            return $mail->hasTo('info@asnenafrica.org');
        });
    }

    public function test_admin_can_queue_a_mass_email_to_registrants(): void
    {
        foreach (['events.view', 'events.update'] as $permission) {
            Permission::findOrCreate($permission, 'web');
        }
        $role = Role::findOrCreate('admin', 'web');
        $role->syncPermissions(Permission::all());
        $user = User::factory()->create();
        $user->assignRole($role);

        $event = Event::create([
            'title' => 'NCPWD Webinar',
            'slug' => 'why-registration-matters-ncpwd',
            'starts_at' => now()->addMonth(),
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);
        EventRegistration::create([
            'event_id' => $event->id,
            'name' => 'Amina Otieno',
            'email' => 'amina@example.com',
            'status' => 'confirmed',
        ]);

        Mail::fake();

        $this->actingAs($user)->post(route('admin.events.registrations.email', $event), [
            'subject' => '{event} joining link',
            'body' => 'Hello {name}, here is your link.',
        ])->assertRedirect();

        Mail::assertQueued(EventRegistrantMessage::class, 1);
    }
}
