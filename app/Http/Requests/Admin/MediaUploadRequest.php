<?php

namespace App\Http\Requests\Admin;

use App\Enums\ConsentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class MediaUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('media.upload') ?? false;
    }

    public function rules(): array
    {
        return [
            'file' => ['nullable', 'file', 'max:10240'],
            'files' => ['nullable', 'array', 'min:1', 'max:40'],
            'files.*' => ['file', 'max:10240'],
            'alt' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string'],
            'consent_status' => ['nullable', 'string', Rule::enum(ConsentStatus::class)],
            'folder' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $hasSingle = $this->hasFile('file');
            $hasMany = $this->hasFile('files');

            if (! $hasSingle && ! $hasMany) {
                $validator->errors()->add('files', 'Please choose one or more files to upload.');
            }
        });
    }
}
