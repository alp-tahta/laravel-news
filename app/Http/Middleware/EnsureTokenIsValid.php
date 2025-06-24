<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->method() === 'GET') {
            return $next($request);
        }
        $token = $request->header('Authorization');
        if ($token !== '2BH52wAHrAymR7wP3CASt') {
            return response()->json([
                'status' => 'error',
                'message' => 'Yetki tokeni geçersiz.',
            ], 401);
        }

        return $next($request);
    }
}