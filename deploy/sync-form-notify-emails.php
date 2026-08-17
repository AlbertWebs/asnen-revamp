<?php

use App\Models\FormDefinition;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$notify = strtolower(trim((string) config('mail.form_notify_address', 'info@asnenafrica.org')));
$updated = 0;

foreach (FormDefinition::query()->get() as $form) {
    $emails = collect($form->notify_emails ?? [])
        ->filter(fn ($email) => is_string($email) && $email !== '')
        ->map(fn ($email) => strtolower(trim($email)))
        ->unique()
        ->values();

    if ($emails->contains($notify)) {
        continue;
    }

    $form->notify_emails = $emails->prepend($notify)->all();
    $form->save();
    $updated++;
}

echo "OK forms_updated={$updated} notify={$notify}\n";
