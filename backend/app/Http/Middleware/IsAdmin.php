<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        if (! $request->user()->role->libelle !== 'admin') {
            return response()->json(['message' => 'Accès refusé : Réservé aux administrateurs'], 403);
        }
        return $next($request);
    }
}
