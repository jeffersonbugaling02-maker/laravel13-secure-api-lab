<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Get the authenticated user
        $user = $request->user();

        // Return 401 if the user is not authenticated
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Check if the user's role is allowed
        if (!in_array($user->role, $roles, true)) {
            return response()->json([
                'message' => 'Forbidden. You do not have permission to access this resource.'
            ], 403);
        }

        // Allow access if the user's role is permitted
        return $next($request);
    }
}