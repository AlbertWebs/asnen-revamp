<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSite\Concerns\RespondsToAjaxForms;
use App\Http\Requests\PublicSite\ContactFormRequest;
use App\Models\FormDefinition;
use App\Repositories\PageRepository;
use App\Services\FormSubmissionService;
use App\Services\HtmlSanitizer;
use App\Services\Settings;

class ContactController extends Controller
{
    use RespondsToAjaxForms;

    public function __construct(
        private PageRepository $pages,
        private HtmlSanitizer $sanitizer,
        private FormSubmissionService $forms,
        private Settings $settings,
    ) {}

    public function show()
    {
        $page = $this->pages->findBySlug('contact');

        return view('public.contact', [
            'page' => $page,
            'sanitizer' => $this->sanitizer,
            'contactEmail' => $this->settings->get('contact.email'),
            'contactPhone' => $this->settings->get('contact.phone_primary'),
            'contactPhoneSecondary' => $this->settings->get('contact.phone_secondary'),
            'contactCity' => $this->settings->get('contact.city'),
        ]);
    }

    public function store(ContactFormRequest $request)
    {
        $form = FormDefinition::where('slug', 'contact')->where('is_active', true)->firstOrFail();
        $submission = $this->forms->store($form, $request->formData(), $request);

        return $this->formSuccessResponse(
            $request,
            $submission->confirmation_token,
            'contact',
            $form->success_message
        );
    }
}
