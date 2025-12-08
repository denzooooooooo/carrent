<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LanguageSwitcher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Check if user is authenticated and has a preferred language
            if (auth()->check() && auth()->user() && auth()->user()->preferred_language) {
                $locale = auth()->user()->preferred_language;
                Session::put('locale', $locale);
            } else {
                // Get locale from session, default to app locale (fr)
                $locale = Session::get('locale', config('app.locale', 'fr'));
            }

            // Set the application locale
            App::setLocale($locale);
        } catch (\Exception $e) {
            // Fallback to default locale if anything goes wrong
            App::setLocale(config('app.locale', 'fr'));
        }

        return $next($request);
    }
}
