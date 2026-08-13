<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PublicUrlOrPath implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (! is_string($value)) {
            $fail('The :attribute must be a valid URL or site path.');

            return;
        }

        $trimmed = trim($value);

        if ($trimmed === '' || str_starts_with($trimmed, '/') || str_starts_with($trimmed, '#')) {
            return;
        }

        if (filter_var($trimmed, FILTER_VALIDATE_URL)) {
            return;
        }

        // Allow scheme-relative or bare domains editors often paste.
        if (preg_match('/^(https?:)?\/\/[^\s]+$/i', $trimmed)) {
            return;
        }

        $fail('The :attribute must be a valid URL or site path (for example /impact/stories).');
    }
}
