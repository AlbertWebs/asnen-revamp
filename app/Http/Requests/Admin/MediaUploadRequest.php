<?php

namespace App\Http\Requests\Admin;

use App\Enums\ConsentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MediaUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('media.upload') ?? false;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'max:10240'],
            'alt' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string'],
            'consent_status' => ['nullable', 'string', Rule::enum(ConsentStatus::class)],
            'folder' => ['nullable', 'string', 'max:100'],
        ];
    }
}
