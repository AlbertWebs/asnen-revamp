<?php

namespace App\Http\Requests\PublicSite;

class PartnerFormRequest extends BaseFormRequest
{
    protected function formRules(): array
    {
        return [
            'organisation' => ['required', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'proposal' => ['required', 'string', 'max:5000'],
        ];
    }
}
