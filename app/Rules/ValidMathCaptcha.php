<?php

namespace App\Rules;

use App\Services\MathCaptcha;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Request;

class ValidMathCaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $token = Request::input('math_token');
        $ok = app(MathCaptcha::class)->check(is_string($token) ? $token : null, $value);

        if (! $ok) {
            $fail('Please solve the arithmetic check correctly to continue.');
        }
    }
}
