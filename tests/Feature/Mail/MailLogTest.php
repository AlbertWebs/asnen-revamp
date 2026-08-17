<?php

namespace Tests\Feature\Mail;

use App\Enums\MailLogStatus;
use App\Mail\MailerTestMessage;
use App\Models\MailLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MailLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_send_is_logged_as_sent(): void
    {
        Mail::to('info@asnenafrica.org')->send(new MailerTestMessage('array'));

        $log = MailLog::query()->latest('id')->first();

        $this->assertNotNull($log);
        $this->assertSame(MailLogStatus::Sent, $log->status);
        $this->assertContains('info@asnenafrica.org', $log->to_addresses);
        $this->assertSame('notify@asnenafrica.org', $log->from_address);
        $this->assertSame('MailerTestMessage', $log->mailable);
        $this->assertNotNull($log->sent_at);
    }

    public function test_admin_can_view_mail_logs(): void
    {
        Permission::findOrCreate('mail_logs.view', 'web');
        $role = Role::findOrCreate('admin', 'web');
        $role->givePermissionTo('mail_logs.view');
        $user = User::factory()->create();
        $user->assignRole($role);

        $log = MailLog::create([
            'mailer' => 'array',
            'mailable' => 'MailerTestMessage',
            'from_address' => 'notify@asnenafrica.org',
            'to_addresses' => ['info@asnenafrica.org'],
            'subject' => 'ASNEN mailer test',
            'status' => MailLogStatus::Sent,
            'sent_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('admin.mail-logs.index'))
            ->assertOk()
            ->assertSee('info@asnenafrica.org')
            ->assertSee('ASNEN mailer test');

        $this->actingAs($user)
            ->get(route('admin.mail-logs.show', $log))
            ->assertOk()
            ->assertSee('notify@asnenafrica.org');
    }

    public function test_guest_cannot_view_mail_logs(): void
    {
        $this->get(route('admin.mail-logs.index'))->assertRedirect();
    }
}
