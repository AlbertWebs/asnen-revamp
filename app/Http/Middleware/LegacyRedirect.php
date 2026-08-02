<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LegacyRedirect
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! in_array($request->method(), ['GET', 'HEAD'], true)) {
            return $next($request);
        }

        $path = '/'.ltrim($request->path(), '/');

        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        $redirect = Redirect::query()
            ->where('is_active', true)
            ->where('from_path', $path)
            ->first();

        if (! $redirect) {
            return $next($request);
        }

        $redirect->increment('hits');

        return redirect($redirect->to_path, $redirect->status_code);
    }
}
