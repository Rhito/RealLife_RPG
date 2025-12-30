<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $admin = $request->user('admin');
        if (!$admin) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        if (!in_array($admin->role->value, $roles)) {
            return response()->json(['message' => 'You do not have permission to access this resource.'], 403);
        }

        if ($admin->not_allowed) {
            return response()->json(['message' => 'Your account has been locked by an administrator.'], 403);
        }

        return $next($request);
    }
}
