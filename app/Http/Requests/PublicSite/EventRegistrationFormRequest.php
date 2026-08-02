<?php

namespace App\Http\Requests\PublicSite;

class EventRegistrationFormRequest extends BaseFormRequest
{
    protected function formRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'organization' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'consent_marketing' => ['nullable', 'boolean'],
        ];
    }
}
