<?php

namespace App\Http\Requests\PublicSite;

use Illuminate\Validation\Rule;

class ToolkitRequestFormRequest extends BaseFormRequest
{
    protected function formRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'organisation' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'string', Rule::in([
                'caregiver',
                'teacher',
                'facilitator',
                'organisation',
                'health_worker',
                'other',
            ])],
            'location' => ['nullable', 'string', 'max:255'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:500'],
            'message' => ['nullable', 'string', 'max:5000'],
            'publication_slug' => ['required', 'string', 'max:255'],
            'publication_title' => ['required', 'string', 'max:255'],
        ];
    }
}
