<?php

namespace App\Http\Middleware;

use App\useCheckJWT;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckAuthentication
{
    use useCheckJWT;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $check): Response
    {
        $shared = $this->checkJwtAuth($request);

        // If strict mode and not authed → redirect
        if (!$shared['isAuthed'] && $check !== 'default') {
            return redirect()->route('login')->withErrors([
                'warn' => 'Harap lakukan login untuk melanjutkan',
            ]);
        }

        Inertia::share($shared);

        return $next($request);
    }
}
