<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Host pages need a business profile. Users without one are sent to business registration. */
class EnsureBusinessHost
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->business) {
            return redirect()->route('host.register')
                ->with('warning', 'Set up your business profile to manage party packs.');
        }

        return $next($request);
    }
}
