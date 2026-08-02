<?php

namespace App\Http\Requests\PublicSite;

use Illuminate\Validation\Rule;

class MembershipFormRequest extends BaseFormRequest
{
    protected function formRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'membership_type' => ['required', Rule::in(['individual', 'organizational'])],
            'motivation' => ['required', 'string', 'max:5000'],
        ];
    }
}
