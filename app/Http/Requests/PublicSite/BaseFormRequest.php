<?php

namespace App\Http\Requests\PublicSite;

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
            ['website' => ['prohibited']],
            $this->formRules()
        );
    }

    /**
     * @return array<string, mixed>
     */
    abstract protected function formRules(): array;
}
