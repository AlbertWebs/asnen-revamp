<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicSite\NewsletterFormRequest;
use App\Models\FormDefinition;
use App\Models\NewsletterSubscriber;
use App\Services\FormSubmissionService;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function __construct(private FormSubmissionService $forms) {}

    public function subscribe(NewsletterFormRequest $request)
    {
        $form = FormDefinition::where('slug', 'newsletter')->where('is_active', true)->firstOrFail();
        $data = $request->safe()->except('website');

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

        if ($request->expectsJson()) {
            return response()->json(['message' => $form->success_message]);
        }

        return redirect()->route('site.forms.confirmation', [
            'token' => $submission->confirmation_token,
            'type' => 'newsletter',
        ]);
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
