<?php

//created by mohammed 06/01

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string $role  Le rôle requis pour accéder à la page (ex: 'admin')
     */

    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Si l'utilisateur n'est pas connecté -> Login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. On récupère le rôle de l'utilisateur connecté depuis la BDD
        $userRole = Auth::user()->role->name;

        // 3. Vérification stricte
        // Si le rôle de l'user n'est pas celui demandé par la route -> Erreur 403
        if ($userRole !== $role) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas le rôle : ' . $role);
        }

        return $next($request);
    }
}
