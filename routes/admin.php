<?php

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DonationCampaignController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FormSubmissionController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HeroImageController;
use App\Http\Controllers\Admin\MailLogController;
use App\Http\Controllers\Admin\ImpactMetricController;
use App\Http\Controllers\Admin\ImpactStoryController;
use App\Http\Controllers\Admin\MediaAssetController;
use App\Http\Controllers\Admin\NavigationController;
use App\Http\Controllers\Admin\NewsletterSubscriberController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\PublicationController;
use App\Http\Controllers\Admin\RedirectController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WebinarController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('pages', PageController::class);
    Route::post('pages/{page}/publish', [PageController::class, 'publish'])->name('pages.publish');
    Route::post('pages/{page}/unpublish', [PageController::class, 'unpublish'])->name('pages.unpublish');

    Route::resource('programs', ProgramController::class);
    Route::post('programs/{program}/publish', [ProgramController::class, 'publish'])->name('programs.publish');
    Route::post('programs/{program}/unpublish', [ProgramController::class, 'unpublish'])->name('programs.unpublish');

    Route::resource('impact-stories', ImpactStoryController::class);
    Route::post('impact-stories/{impact_story}/publish', [ImpactStoryController::class, 'publish'])->name('impact-stories.publish');
    Route::post('impact-stories/{impact_story}/unpublish', [ImpactStoryController::class, 'unpublish'])->name('impact-stories.unpublish');
    Route::post('impact-stories/{impact_story}/approve-safeguarding', [ImpactStoryController::class, 'approveSafeguarding'])->name('impact-stories.approve-safeguarding');
    Route::post('impact-stories/{impact_story}/partners/upload', [ImpactStoryController::class, 'uploadPartners'])->name('impact-stories.partners.upload');
    Route::post('impact-stories/{impact_story}/partners/attach', [ImpactStoryController::class, 'attachPartner'])->name('impact-stories.partners.attach');
    Route::patch('impact-stories/{impact_story}/partners/{partner}', [ImpactStoryController::class, 'updateStoryPartner'])->name('impact-stories.partners.update');
    Route::delete('impact-stories/{impact_story}/partners/{partner}', [ImpactStoryController::class, 'detachPartner'])->name('impact-stories.partners.detach');

    Route::resource('impact-metrics', ImpactMetricController::class);
    Route::post('impact-metrics/{impact_metric}/publish', [ImpactMetricController::class, 'publish'])->name('impact-metrics.publish');
    Route::post('impact-metrics/{impact_metric}/unpublish', [ImpactMetricController::class, 'unpublish'])->name('impact-metrics.unpublish');

    Route::resource('regions', RegionController::class);
    Route::post('regions/{region}/publish', [RegionController::class, 'publish'])->name('regions.publish');
    Route::post('regions/{region}/unpublish', [RegionController::class, 'unpublish'])->name('regions.unpublish');

    Route::resource('events', EventController::class);
    Route::post('events/{event}/publish', [EventController::class, 'publish'])->name('events.publish');
    Route::post('events/{event}/unpublish', [EventController::class, 'unpublish'])->name('events.unpublish');
    Route::post('events/{event}/registration', [EventController::class, 'toggleRegistration'])->name('events.registration.toggle');
    Route::get('events/{event}/registrations', [EventController::class, 'registrations'])->name('events.registrations');
    Route::post('events/{event}/registrations/email', [EventController::class, 'emailRegistrants'])->name('events.registrations.email');

    Route::resource('webinars', WebinarController::class);
    Route::post('webinars/{webinar}/publish', [WebinarController::class, 'publish'])->name('webinars.publish');
    Route::post('webinars/{webinar}/unpublish', [WebinarController::class, 'unpublish'])->name('webinars.unpublish');

    Route::resource('publications', PublicationController::class);
    Route::post('publications/{publication}/publish', [PublicationController::class, 'publish'])->name('publications.publish');
    Route::post('publications/{publication}/unpublish', [PublicationController::class, 'unpublish'])->name('publications.unpublish');

    Route::resource('articles', ArticleController::class);
    Route::post('articles/{article}/publish', [ArticleController::class, 'publish'])->name('articles.publish');
    Route::post('articles/{article}/unpublish', [ArticleController::class, 'unpublish'])->name('articles.unpublish');

    Route::resource('partners', PartnerController::class);
    Route::post('partners/{partner}/publish', [PartnerController::class, 'publish'])->name('partners.publish');
    Route::post('partners/{partner}/unpublish', [PartnerController::class, 'unpublish'])->name('partners.unpublish');

    Route::resource('team-members', TeamMemberController::class);
    Route::post('team-members/{team_member}/publish', [TeamMemberController::class, 'publish'])->name('team-members.publish');
    Route::post('team-members/{team_member}/unpublish', [TeamMemberController::class, 'unpublish'])->name('team-members.unpublish');

    Route::resource('media', MediaAssetController::class)->parameters(['media' => 'medium']);

    Route::get('hero-images', [HeroImageController::class, 'edit'])->name('hero-images.edit');
    Route::put('hero-images', [HeroImageController::class, 'update'])->name('hero-images.update');

    Route::get('form-submissions/export', [FormSubmissionController::class, 'export'])->name('form-submissions.export');
    Route::resource('form-submissions', FormSubmissionController::class)->only(['index', 'show', 'update']);

    Route::get('mail-logs', [MailLogController::class, 'index'])->name('mail-logs.index');
    Route::get('mail-logs/{mail_log}', [MailLogController::class, 'show'])->name('mail-logs.show');

    Route::get('newsletter-subscribers/export', [NewsletterSubscriberController::class, 'export'])->name('newsletter-subscribers.export');
    Route::resource('newsletter-subscribers', NewsletterSubscriberController::class)->only(['index']);

    Route::get('settings/{group}', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings/{group}', [SettingController::class, 'update'])->name('settings.update');

    Route::resource('navigation', NavigationController::class)->only(['index', 'edit', 'update']);

    Route::resource('announcements', AnnouncementController::class);
    Route::resource('redirects', RedirectController::class);

    Route::resource('faqs', FaqController::class);
    Route::post('faqs/{faq}/publish', [FaqController::class, 'publish'])->name('faqs.publish');
    Route::post('faqs/{faq}/unpublish', [FaqController::class, 'unpublish'])->name('faqs.unpublish');

    Route::resource('galleries', GalleryController::class);
    Route::post('galleries/{gallery}/publish', [GalleryController::class, 'publish'])->name('galleries.publish');
    Route::post('galleries/{gallery}/unpublish', [GalleryController::class, 'unpublish'])->name('galleries.unpublish');
    Route::post('galleries/{gallery}/upload', [GalleryController::class, 'upload'])->name('galleries.upload');
    Route::patch('galleries/{gallery}/items/{item}', [GalleryController::class, 'updateItem'])->name('galleries.items.update');
    Route::post('galleries/{gallery}/items/move', [GalleryController::class, 'moveItems'])->name('galleries.items.move');
    Route::delete('galleries/{gallery}/items/{item}', [GalleryController::class, 'destroyItem'])->name('galleries.items.destroy');

    Route::resource('donation-campaigns', DonationCampaignController::class);
    Route::post('donation-campaigns/{donation_campaign}/publish', [DonationCampaignController::class, 'publish'])->name('donation-campaigns.publish');
    Route::post('donation-campaigns/{donation_campaign}/unpublish', [DonationCampaignController::class, 'unpublish'])->name('donation-campaigns.unpublish');

    Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);
});
