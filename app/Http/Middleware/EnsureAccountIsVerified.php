<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si l'utilisateur n'est pas connecté, laisser passer (géré par auth middleware)
        if (!$user) {
            return $next($request);
        }

        // Si l'utilisateur est déjà vérifié, laisser passer
        if ($user->is_verified) {
            return $next($request);
        }

        // Si l'utilisateur est sur la page de vérification ou les routes de vérification, laisser passer
        if ($request->is('verify-account') || 
            $request->is('verify-code') || 
            $request->is('resend-verification') || 
            $request->is('change-verification-method') ||
            $request->is('send-email-verification') ||
            $request->is('send-sms-verification') ||
            $request->is('logout')) {
            return $next($request);
        }

        // Rediriger vers la page de vérification
        return redirect()->route('verify.show')
            ->with('warning', 'Veuillez vérifier votre compte pour continuer.');
    }
}
