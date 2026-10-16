<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSupervisor
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(
            $request->user()?->isSupervisor()
                && $request->user()?->email_verified_at !== null
                && $request->user()?->company_id !== null,
            403,
        );

        return $next($request);
    }
}
