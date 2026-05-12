<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            abort(401);
        }

        foreach ($roles as $roleName) {
            $role = Role::from(constant(Role::class . '::' . $roleName)->value);
            if ($request->user()->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'Недостаточно прав доступа.');
    }
}
