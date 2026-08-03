<?php

namespace App\Http\Controllers\PublicSite\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait RespondsToAjaxForms
{
    protected function formSuccessResponse(
        Request $request,
        string $token,
        string $type,
        ?string $message = null,
    ): JsonResponse|RedirectResponse {
        $redirect = route('site.forms.confirmation', [
            'token' => $token,
            'type' => $type,
        ]);

        $message ??= 'Thank you for your submission.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => $redirect,
            ]);
        }

        return redirect()->to($redirect);
    }
}
