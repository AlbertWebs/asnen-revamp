<?php

namespace App\Http\Requests\PublicSite;

use App\Rules\ValidMathCaptcha;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return array_merge(
            [
                'website' => ['prohibited'],
                'math_token' => ['required', 'string'],
                'math_answer' => ['required', 'integer', new ValidMathCaptcha],
            ],
            $this->formRules()
        );
    }

    /**
     * Validated payload without antispam fields.
     *
     * @return array<string, mixed>
     */
    public function formData(): array
    {
        return $this->safe()->except(['website', 'math_token', 'math_answer']);
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'website.prohibited' => 'Spam detected.',
            'math_token.required' => 'Please complete the arithmetic check.',
            'math_answer.required' => 'Please complete the arithmetic check.',
            'math_answer.integer' => 'Enter a whole number for the arithmetic check.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    abstract protected function formRules(): array;
}
