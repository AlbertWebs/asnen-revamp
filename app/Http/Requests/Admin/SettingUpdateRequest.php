<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('settings.update') ?? false;
    }

    public function rules(): array
    {
        return [
            'settings' => ['nullable', 'array'],
            'settings.*.key' => ['required_with:settings', 'string', 'max:255'],
            'settings.*.value' => ['nullable'],
            'settings.*.is_public' => ['nullable', 'boolean'],
            'logo_id' => ['nullable', 'integer', 'exists:media_assets,id'],
        ];
    }
}
