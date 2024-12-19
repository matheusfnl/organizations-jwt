<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\{Request, Response as HttpResponse};
use Symfony\Component\HttpFoundation\Response;

class AuthorizeOrganizationOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $organization = $request->route('organization');

        if ($organization->owner_id !== auth()->id()) {
            abort(HttpResponse::HTTP_UNAUTHORIZED, 'Unauthorized');
        }

        return $next($request);
    }
}
