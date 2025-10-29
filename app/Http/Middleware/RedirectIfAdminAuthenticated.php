<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAdminAuthenticated
{
    /**
     * Gère une requête entrante. Si l'administrateur est déjà connecté via le garde 'admin',
     * le redirige vers le tableau de bord.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie spécifiquement si l'utilisateur est authentifié avec le garde 'admin'
        if (Auth::guard('admin')->check()) {
            // Utilise la route nommée 'admin.dashboard' pour la redirection
            return redirect()->route('admin.dashboard');
        }

        // Si l'utilisateur n'est pas authentifié, permet l'accès (vers la page de connexion)
        //return $next($request);
        return redirect()->route('admin.login'); 

    }
}
