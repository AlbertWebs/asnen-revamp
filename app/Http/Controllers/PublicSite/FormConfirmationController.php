<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use App\Models\FormDefinition;
use App\Models\FormSubmission;
use Illuminate\Http\Request;

class FormConfirmationController extends Controller
{
    public function __invoke(Request $request, string $token)
    {
        $type = $request->get('type', 'form');
        $message = 'Thank you for your submission.';

        if ($type === 'event') {
            $registrationId = session('event_registration_token_'.$token);
            $registration = $registrationId
                ? EventRegistration::find($registrationId)
                : null;

            if ($registration) {
                $message = 'Your event registration has been received. We will be in touch with details.';
            }
        } else {
            $submission = FormSubmission::where('confirmation_token', $token)->first();
            if ($submission?->formDefinition?->success_message) {
                $message = $submission->formDefinition->success_message;
            } elseif ($form = FormDefinition::where('slug', $type)->first()) {
                $message = $form->success_message ?? $message;
            }
        }

        return view('public.form-confirmation', compact('message', 'type'));
    }
}
