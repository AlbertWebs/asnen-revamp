<?php

namespace App\Providers;

use App\Listeners\RecordOutgoingMail;
use App\Services\Donations\NullPaymentGateway;
use App\Services\Donations\PaymentGatewayInterface;
use App\Services\HtmlSanitizer;
use App\Services\Settings;
use App\View\Composers\PublicLayoutComposer;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Settings::class);
        $this->app->singleton(HtmlSanitizer::class);
        $this->app->bind(PaymentGatewayInterface::class, NullPaymentGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        Event::listen(MessageSending::class, [RecordOutgoingMail::class, 'sending']);
        Event::listen(MessageSent::class, [RecordOutgoingMail::class, 'sent']);

        View::composer(['layouts.public', 'public.*'], PublicLayoutComposer::class);

        View::composer(['layouts.admin', 'layouts.guest', 'admin.partials.sidebar', 'components.application-logo'], function ($view) {
            $view->with('siteLogoUrl', app(Settings::class)->logoUrl());
        });
    }
}
