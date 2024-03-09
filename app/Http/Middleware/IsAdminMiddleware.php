<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddleware
{
    /**
     * Check if logged user is an administrator.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_if($request->user()->role_id !== Role::ADMINISTRATOR->value, Response::HTTP_FORBIDDEN);

        return $next($request);
    }
}
