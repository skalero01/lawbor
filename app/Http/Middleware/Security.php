<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Security
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $catch = $this->userSecurity(auth()->user());
        }
        if ($catch) {
            return $catch;
        }
        return $next($request);
    }

    private function userSecurity(Authenticatable $user)
    {
        if (
            method_exists($user, 'shouldEnforcePasswordRequest') &&
            $user->shouldEnforcePasswordRequest() &&
            ! auth()->user()->isActing()
        ) {
            return redirect()->route('password.insecure.request');
        }
    }
}
