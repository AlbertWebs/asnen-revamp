<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSite\Concerns\RespondsToAjaxForms;
use App\Http\Requests\PublicSite\DonateFormRequest;
use App\Http\Requests\PublicSite\MembershipFormRequest;
use App\Http\Requests\PublicSite\PartnerFormRequest;
use App\Http\Requests\PublicSite\VolunteerFormRequest;
use App\Models\DonationCampaign;
use App\Models\FormDefinition;
use App\Models\MembershipPlan;
use App\Models\Program;
use App\Repositories\PageRepository;
use App\Services\Donations\PaymentGatewayInterface;
use App\Services\FormSubmissionService;
use App\Services\HtmlSanitizer;

class GetInvolvedController extends Controller
{
    use RespondsToAjaxForms;

    public function __construct(
        private PageRepository $pages,
        private HtmlSanitizer $sanitizer,
        private FormSubmissionService $forms,
        private PaymentGatewayInterface $payments,
    ) {}

    public function index()
    {
        $page = $this->pages->findBySlug('get-involved');
        abort_unless($page, 404);

        return view('public.get-involved.index', [
            'page' => $page,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $page->bannerImages(),
        ]);
    }

    public function membership()
    {
        $page = $this->pages->findBySlug('get-involved-membership');
        abort_unless($page, 404);

        $plans = MembershipPlan::published()->orderBy('sort_order')->get();

        return view('public.get-involved.membership', [
            'page' => $page,
            'plans' => $plans,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $page->bannerImages(),
        ]);
    }

    public function storeMembership(MembershipFormRequest $request)
    {
        return $this->storeForm('membership', $request->formData(), $request);
    }

    public function volunteer()
    {
        $page = $this->pages->findBySlug('get-involved-volunteer');
        abort_unless($page, 404);

        return view('public.get-involved.volunteer', [
            'page' => $page,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $page->bannerImages(),
        ]);
    }

    public function storeVolunteer(VolunteerFormRequest $request)
    {
        return $this->storeForm('volunteer', $request->formData(), $request);
    }

    public function partner()
    {
        $page = $this->pages->findBySlug('get-involved-partner');
        abort_unless($page, 404);

        return view('public.get-involved.partner', [
            'page' => $page,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $page->bannerImages(),
        ]);
    }

    public function storePartner(PartnerFormRequest $request)
    {
        return $this->storeForm('partner', $request->formData(), $request);
    }

    public function partnershipBrief()
    {
        return view('public.get-involved.partnership-brief');
    }

    public function donate()
    {
        $page = $this->pages->findBySlug('get-involved-donate');
        abort_unless($page, 404);

        $campaign = DonationCampaign::published()->first();
        $programs = Program::published()->orderBy('sort_order')->get();

        return view('public.get-involved.donate', [
            'page' => $page,
            'campaign' => $campaign,
            'programs' => $programs,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $page->bannerImages(),
        ]);
    }

    public function storeDonate(DonateFormRequest $request)
    {
        return $this->storeForm('donate', $request->formData(), $request);
    }

    protected function storeForm(string $slug, array $data, $request)
    {
        $form = FormDefinition::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $submission = $this->forms->store($form, $data, $request);

        return $this->formSuccessResponse(
            $request,
            $submission->confirmation_token,
            $slug,
            $form->success_message
        );
    }
}
