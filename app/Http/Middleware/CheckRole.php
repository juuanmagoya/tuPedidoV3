<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        // No autenticado
        if (!$user) {
            abort(403);
        }

        // Admin siempre tiene acceso
        if ($user->role === User::ROLE_ADMIN) {
            return $next($request);
        }

        // Si el rol del usuario está permitido
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Acceso denegado
        abort(403, 'No tienes permisos para acceder a esta sección.');
    }
}
