<?php

namespace App\Http\Middleware;

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
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $check): Response
    {
        $shared = [
            'isAuthed' => false,
            'auth' => [
                'token' => null,
                'user' => null,
            ],
        ];

        try {
            // Grab token from cookie
            $jwt = json_decode($request->cookie('session_token'), true);

            if (!$jwt) {
                throw new JWTException('Token missing');
            }

            // Parse token & validate (no DB lookup)
            $payload = JWTAuth::setToken($jwt['token'])->getPayload();

            // Extract your stored claims
            $userData = $jwt['user'];

            // Fake user object so $request->user() works
            $request->setUserResolver(fn() => (object) $userData);

            $shared['isAuthed'] = true;
            $shared['auth'] = [
                'token' => $jwt['token'] ?? null,
                'user' => $jwt['user'] ?? null,
            ];
        } catch (TokenExpiredException | TokenInvalidException | JWTException $e) {
            // If strict check required (not default) → redirect
            if ($check !== 'default') {
                return redirect()->route('login')->withErrors([
                    'warn' => $e instanceof TokenExpiredException
                        ? 'Sesi sudah berakhir, harap login kembali'
                        : 'Harap lakukan login untuk melanjutkan',
                ]);
            }
            // Else → just stay guest (isAuthed = false)
        }

        Inertia::share($shared);
        return $next($request);
    }
}
