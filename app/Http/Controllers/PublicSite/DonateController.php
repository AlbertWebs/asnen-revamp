<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;

class DonateController extends Controller
{
    public function success()
    {
        return view('public.donate.success');
    }

    public function cancel()
    {
        return view('public.donate.cancel');
    }
}
