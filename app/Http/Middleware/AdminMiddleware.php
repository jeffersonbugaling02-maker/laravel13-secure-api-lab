<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get the authenticated user
        $user = $request->user();

        // Return 401 if the user is not authenticated
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Allow only admin users
        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Forbidden. Admin access only.'
            ], 403);
        }

        // Allow the admin to continue
        return $next($request);
    }
}