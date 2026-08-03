<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSite\Concerns\RespondsToAjaxForms;
use App\Http\Requests\PublicSite\NewsletterFormRequest;
use App\Models\FormDefinition;
use App\Models\NewsletterSubscriber;
use App\Services\FormSubmissionService;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    use RespondsToAjaxForms;

    public function __construct(private FormSubmissionService $forms) {}

    public function subscribe(NewsletterFormRequest $request)
    {
        $form = FormDefinition::where('slug', 'newsletter')->where('is_active', true)->firstOrFail();
        $data = $request->formData();

        NewsletterSubscriber::updateOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'] ?? null,
                'status' => 'subscribed',
                'source' => 'website',
                'consent_at' => now(),
                'unsubscribed_at' => null,
            ]
        );

        $submission = $this->forms->store($form, $data, $request);

        return $this->formSuccessResponse(
            $request,
            $submission->confirmation_token,
            'newsletter',
            $form->success_message ?? 'You are subscribed. Thank you.'
        );
    }

    public function unsubscribe(Request $request)
    {
        $email = $request->get('email');

        if ($email) {
            NewsletterSubscriber::where('email', $email)->update([
                'status' => 'unsubscribed',
                'unsubscribed_at' => now(),
            ]);
        }

        return view('public.newsletter-unsubscribe', compact('email'));
    }
}
