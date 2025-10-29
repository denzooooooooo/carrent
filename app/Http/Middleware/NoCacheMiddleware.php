<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoCacheMiddleware
{
    /**
     * Empêche le navigateur de mettre en cache la page, forçant un rechargement lors de l'utilisation du bouton "Retour".
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Si l'utilisateur est connecté (ou si la page est protégée par 'auth'),
        // nous appliquons les en-têtes anti-cache.
        if (auth()->check() || $request->routeIs('profile')) { // Vous pouvez ajouter d'autres conditions si nécessaire
            $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        }

        return $response;
    }
}
