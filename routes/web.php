<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicSite\ContactController;
use App\Http\Controllers\PublicSite\DonateController;
use App\Http\Controllers\PublicSite\EventController;
use App\Http\Controllers\PublicSite\FormConfirmationController;
use App\Http\Controllers\PublicSite\GalleryController;
use App\Http\Controllers\PublicSite\GetInvolvedController;
use App\Http\Controllers\PublicSite\HomeController;
use App\Http\Controllers\PublicSite\ImpactController;
use App\Http\Controllers\PublicSite\MathCaptchaController;
use App\Http\Controllers\PublicSite\NewsletterController;
use App\Http\Controllers\PublicSite\PageController;
use App\Http\Controllers\PublicSite\PreviewController;
use App\Http\Controllers\PublicSite\ProgramController;
use App\Http\Controllers\PublicSite\ResourceController;
use App\Http\Controllers\PublicSite\SearchController;
use App\Http\Controllers\PublicSite\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Site Routes
|--------------------------------------------------------------------------
*/

Route::name('site.')->group(function () {
    Route::get('/', HomeController::class)->name('home');

    Route::get('/search', SearchController::class)->name('search');

    Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

    Route::get('/preview/pages/{page}', PreviewController::class)
        ->middleware('signed')
        ->name('preview.page');

    Route::get('/forms/confirmation/{token}', FormConfirmationController::class)->name('forms.confirmation');
    Route::get('/forms/math-challenge', MathCaptchaController::class)
        ->middleware('throttle:30,1')
        ->name('forms.math-challenge');

    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
        ->middleware('throttle:5,1')
        ->name('newsletter.subscribe');
    Route::get('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

    Route::get('/donate/success', [DonateController::class, 'success'])->name('donate.success');
    Route::get('/donate/cancel', [DonateController::class, 'cancel'])->name('donate.cancel');

    // About
    Route::redirect('/about', '/about/who-we-are')->name('about');
    Route::get('/about/who-we-are', fn () => app(PageController::class)->show('about/who-we-are'))->name('about.who-we-are');
    Route::get('/about/mission-vision-values', fn () => redirect()->route('site.about.who-we-are', status: 301)->fragment('vision'))->name('about.mission');
    Route::get('/about/our-story', fn () => redirect()->route('site.about.who-we-are', status: 301)->fragment('story'))->name('about.story');
    Route::get('/about/leadership', fn () => app(PageController::class)->show('about/leadership'))->name('about.leadership');
    Route::get('/about/governance', fn () => redirect()->route('site.about.leadership', status: 301)->fragment('governance'))->name('about.governance');
    Route::get('/about/partners', fn () => app(PageController::class)->show('about/partners'))->name('about.partners');

    // What We Do / Programs
    Route::get('/what-we-do', [ProgramController::class, 'index'])->name('programs.index');
    Route::get('/what-we-do/{slug}', [ProgramController::class, 'show'])->name('programs.show');

    // Impact
    Route::get('/impact', [ImpactController::class, 'overview'])->name('impact.overview');
    Route::get('/impact/komolion', [ImpactController::class, 'komolion'])->name('impact.komolion');
    Route::get('/impact/stories', [ImpactController::class, 'stories'])->name('impact.stories');
    Route::get('/impact/stories/{slug}', [ImpactController::class, 'showStory'])->name('impact.stories.show');
    Route::get('/impact/reports', [ImpactController::class, 'reports'])->name('impact.reports');
    Route::get('/impact/regions', [ImpactController::class, 'regions'])->name('impact.regions');

    // Events & Learning
    Route::prefix('events-learning')->name('events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/upcoming', [EventController::class, 'upcoming'])->name('upcoming');
        Route::get('/past', [EventController::class, 'past'])->name('past');
        Route::get('/webinars', [EventController::class, 'webinars'])->name('webinars');
        Route::get('/ubuntu-conference', [EventController::class, 'ubuntuConference'])->name('ubuntu-conference');
        Route::get('/{slug}', [EventController::class, 'show'])->name('show');
        Route::post('/{slug}/register', [EventController::class, 'register'])
            ->middleware('throttle:5,1')
            ->name('register');
    });

    // Resources
    Route::prefix('resources')->name('resources.')->group(function () {
        Route::get('/', [ResourceController::class, 'index'])->name('index');
        Route::get('/publications', [ResourceController::class, 'publications'])->name('publications');
        Route::get('/publications/{slug}', [ResourceController::class, 'showPublication'])->name('publications.show');
        Route::get('/publications/{slug}/download', [ResourceController::class, 'downloadPublication'])->name('publications.download');
        Route::post('/publications/{slug}/request', [ResourceController::class, 'requestToolkit'])
            ->middleware('throttle:5,1')
            ->name('publications.request');
        Route::get('/toolkits', [ResourceController::class, 'toolkits'])->name('toolkits');
        Route::get('/webinars', [ResourceController::class, 'webinarLibrary'])->name('webinars');
        Route::get('/news', [ResourceController::class, 'news'])->name('news');
        Route::get('/news/{slug}', [ResourceController::class, 'showArticle'])->name('news.show');
        Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
        Route::get('/gallery/{slug}', [GalleryController::class, 'show'])->name('gallery.show');
    });

    // Get Involved
    Route::prefix('get-involved')->name('get-involved.')->group(function () {
        Route::get('/', [GetInvolvedController::class, 'index'])->name('index');
        Route::get('/membership', [GetInvolvedController::class, 'membership'])->name('membership');
        Route::post('/membership', [GetInvolvedController::class, 'storeMembership'])
            ->middleware('throttle:5,1')
            ->name('membership.store');
        Route::get('/volunteer', [GetInvolvedController::class, 'volunteer'])->name('volunteer');
        Route::post('/volunteer', [GetInvolvedController::class, 'storeVolunteer'])
            ->middleware('throttle:5,1')
            ->name('volunteer.store');
        Route::get('/partner', [GetInvolvedController::class, 'partner'])->name('partner');
        Route::post('/partner', [GetInvolvedController::class, 'storePartner'])
            ->middleware('throttle:5,1')
            ->name('partner.store');
        Route::get('/donate', [GetInvolvedController::class, 'donate'])->name('donate');
        Route::post('/donate', [GetInvolvedController::class, 'storeDonate'])
            ->middleware('throttle:5,1')
            ->name('donate.store');
    });

    // Legacy gallery URLs
    Route::redirect('/gallery', '/resources/gallery', 301);
    Route::get('/gallery/{slug}', fn (string $slug) => redirect('/resources/gallery/'.$slug, 301));

    // Contact
    Route::get('/contact', [ContactController::class, 'show'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('contact.store');

    // Utility / legal pages
    Route::get('/accessibility', fn () => app(PageController::class)->show('accessibility'))->name('accessibility');
    Route::get('/privacy', fn () => app(PageController::class)->show('privacy'))->name('privacy');
    Route::get('/terms', fn () => app(PageController::class)->show('terms'))->name('terms');
    Route::get('/cookies', fn () => app(PageController::class)->show('cookies'))->name('cookies');
    Route::get('/faqs', fn () => app(PageController::class)->show('faqs'))->name('faqs');
    Route::get('/safeguarding', fn () => app(PageController::class)->show('safeguarding'))->name('safeguarding');
});

/*
|--------------------------------------------------------------------------
| Auth & Profile (unchanged)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
