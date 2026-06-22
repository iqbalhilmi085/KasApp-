<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        if ($request->isMethod('GET')) {
            return;
        }

        $uri = $request->path();

        if (str_starts_with($uri, 'build/') || str_starts_with($uri, 'assets/') || str_starts_with($uri, 'vendor/')) {
            return;
        }

        if (str_ends_with($uri, '.css') || str_ends_with($uri, '.js') || str_ends_with($uri, '.ico') || str_ends_with($uri, '.png') || str_ends_with($uri, '.jpg') || str_ends_with($uri, '.svg') || str_ends_with($uri, '.woff') || str_ends_with($uri, '.woff2')) {
            return;
        }

        if (!Auth::check()) {
            return;
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $request->method() . ' /' . $uri,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}
