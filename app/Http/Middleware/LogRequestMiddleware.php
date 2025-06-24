<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\RequestLog;

class LogRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Log the request
        RequestLog::create([
            'method' => $request->method(),
            'uri' => $request->getRequestUri(),
            'ip' => $request->ip(),
            'headers' => json_encode($request->headers->all()),
            'body' => json_encode($request->all()),
        ]);
        return $next($request);
    }
}
