<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedOrImpersonating
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        /*
        |--------------------------------------------------------------------------
        | ADMIN IMPERSONATION
        |--------------------------------------------------------------------------
        |
        | If an admin is currently viewing the site as a client,
        | allow access even when the client's email is not verified.
        |
        */

        if (
            $request->session()->has(
                'impersonator_admin_id'
            )
        ) {
            return $next($request);
        }


        /*
        |--------------------------------------------------------------------------
        | NORMAL CLIENT
        |--------------------------------------------------------------------------
        */

        $user = $request->user();

        if (
            $user
            &&
            $user->hasVerifiedEmail()
        ) {
            return $next($request);
        }


        return redirect()
            ->route('verification.notice');
    }
}