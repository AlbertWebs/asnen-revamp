<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\Request;

trait NormalizesNullableIds
{
    protected function nullEmptyIds(Request $request, array $keys): void
    {
        $merge = [];
        foreach ($keys as $key) {
            if (! $request->filled($key)) {
                $merge[$key] = null;
            }
        }
        if ($merge !== []) {
            $request->merge($merge);
        }
    }
}
