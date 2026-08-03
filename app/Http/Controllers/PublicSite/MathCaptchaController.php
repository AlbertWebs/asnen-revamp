<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Services\MathCaptcha;
use Illuminate\Http\JsonResponse;

class MathCaptchaController extends Controller
{
    public function __invoke(MathCaptcha $captcha): JsonResponse
    {
        return response()->json($captcha->issue());
    }
}
