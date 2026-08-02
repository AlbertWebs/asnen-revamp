<?php

namespace App\Http\Requests\PublicSite;

class VolunteerFormRequest extends BaseFormRequest
{
    protected function formRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'skills' => ['required', 'string', 'max:5000'],
            'availability' => ['required', 'string', 'max:255'],
        ];
    }
}
