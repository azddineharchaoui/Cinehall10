<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth('api')->check()) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        }

        if (auth('api')->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized: Admin access required',
            ], 403);
        }

        return $next($request);
    }
}