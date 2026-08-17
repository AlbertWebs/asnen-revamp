<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MailLogStatus;
use App\Http\Controllers\Controller;
use App\Models\MailLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MailLogController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(auth()->user()?->can('mail_logs.view'), 403);

        $status = $request->string('status')->toString();
        $search = $request->string('q')->toString();

        $logs = MailLog::query()
            ->when(
                $status !== '' && MailLogStatus::tryFrom($status),
                fn ($query) => $query->where('status', $status)
            )
            ->search($search)
            ->latest('id')
            ->paginate(40)
            ->withQueryString();

        return view('admin.mail-logs.index', [
            'logs' => $logs,
            'status' => $status,
            'search' => $search,
        ]);
    }

    public function show(MailLog $mailLog): View
    {
        abort_unless(auth()->user()?->can('mail_logs.view'), 403);

        return view('admin.mail-logs.show', [
            'mailLog' => $mailLog,
        ]);
    }
}
