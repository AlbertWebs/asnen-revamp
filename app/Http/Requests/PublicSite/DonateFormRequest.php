<?php

namespace App\Http\Requests\PublicSite;

use App\Models\Program;
use Illuminate\Validation\Rule;

class DonateFormRequest extends BaseFormRequest
{
    protected function formRules(): array
    {
        $programSlugs = Program::published()->pluck('slug')->all();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'program_interest' => ['nullable', 'string', Rule::in(array_merge(['general'], $programSlugs))],
            'message' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
