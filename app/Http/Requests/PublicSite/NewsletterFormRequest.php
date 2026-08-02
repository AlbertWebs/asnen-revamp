<?php

namespace App\Http\Requests\PublicSite;

class NewsletterFormRequest extends BaseFormRequest
{
    protected function formRules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'consent' => ['accepted'],
        ];
    }
}
