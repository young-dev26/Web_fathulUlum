<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        abort_unless($user, 403);

        $effectiveRole = $user->effectiveRole();
        $allowed = in_array($effectiveRole, $roles, true);

        if (! $allowed && $user->role === 'admin' && in_array('admin', $roles, true)) {
            $allowed = true;
        }

        abort_unless($allowed, 403);

        return $next($request);
    }
}
