<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLanguage
{
    public function handle($request, Closure $next)
    {
        $locale = session('locale', 'en'); // Default language is English

        if ($request->has('locale')) {
            $locale = $request->input('locale');
            session(['locale' => $locale]);
        }

        App::setLocale($locale);

        return $next($request);
    }
}
