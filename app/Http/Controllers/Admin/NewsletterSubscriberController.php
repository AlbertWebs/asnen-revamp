<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterSubscriberController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->can('newsletter.view'), 403);

        $subscribers = NewsletterSubscriber::query()->latest()->paginate(50);

        return view('admin.newsletter-subscribers.index', compact('subscribers'));
    }

    public function export(): StreamedResponse
    {
        abort_unless(auth()->user()?->can('newsletter.export'), 403);

        $filename = 'newsletter-subscribers-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Email', 'Name', 'Status', 'Source', 'Consent At', 'Subscribed At']);

            NewsletterSubscriber::query()->orderBy('id')->chunk(100, function ($subscribers) use ($handle) {
                foreach ($subscribers as $subscriber) {
                    fputcsv($handle, [
                        $subscriber->email,
                        $subscriber->name,
                        $subscriber->status,
                        $subscriber->source,
                        $subscriber->consent_at?->toDateTimeString(),
                        $subscriber->created_at?->toDateTimeString(),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
