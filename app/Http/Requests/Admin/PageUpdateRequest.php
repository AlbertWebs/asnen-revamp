<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('pages.update') ?? false;
    }

    public function rules(): array
    {
        $page = $this->route('page');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('pages', 'slug')->ignore($page?->id)],
            'template' => ['nullable', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string'],
            'banner_image_ids' => ['nullable', 'array'],
            'banner_image_ids.*' => ['integer', 'exists:media_assets,id'],
            'status' => ['nullable', 'string'],
            'editor_notes' => ['nullable', 'string'],
            'requires_safeguarding' => ['nullable', 'boolean'],
            'blocks' => ['nullable', 'array'],
            'blocks.*.type' => ['required_with:blocks', 'string', 'max:100'],
            'blocks.*.is_visible' => ['nullable', 'boolean'],
            'blocks.*.content' => ['nullable', 'array'],
            'blocks.*.settings' => ['nullable', 'array'],
            'blocks.*.anchor_id' => ['nullable', 'string', 'max:100'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('blocks') && is_string($this->blocks)) {
            $this->merge([
                'blocks' => json_decode($this->blocks, true) ?? [],
            ]);
        }

        $bannerIds = collect($this->input('banner_image_ids', []))
            ->filter(fn ($id) => filled($id) && $id !== 'null')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $this->merge([
            'banner_image_ids' => $bannerIds,
        ]);
    }
}
