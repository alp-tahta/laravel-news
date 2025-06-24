<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RateLimitOrBlockMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $token = $request->header('Authorization');
        $blockKey = 'blacklist:' . $ip;
        $rateKey = 'rate:' . $ip;

        // If IP is blacklisted, return 429
        if (Cache::has($blockKey)) {
            return response()->json([
                'status' => 'error',
                'message' => 'IP adresiniz çok fazla istekte bulunduğu için 10 dakika boyunca engellendi.'
            ], 429);
        }

        // If no token, count attempts and blacklist after 10
        if (!$token) {
            $attempts = Cache::get($rateKey, 0) + 1;
            Cache::put($rateKey, $attempts, 600); // 10 dakika pencere
            if ($attempts > 10) {
                Cache::put($blockKey, true, 600); // 10 dakika blacklist
                return response()->json([
                    'status' => 'error',
                    'message' => 'IP adresiniz çok fazla istekte bulunduğu için 10 dakika boyunca engellendi.'
                ], 429);
            }
        }

        return $next($request);
    }
}
