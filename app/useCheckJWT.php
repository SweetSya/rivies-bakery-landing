<?php

namespace App;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

trait useCheckJWT
{
    /**
     * Validate JWT from cookie and return auth state.
     */
    public function checkJwtAuth(Request $request): array
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

            // Parse & validate (no DB lookup)
            $payload = JWTAuth::setToken($jwt['token'])->getPayload();

            // Extract stored user claims
            $userData = $jwt['user'];

            // Fake user object so $request->user() works
            $request->setUserResolver(fn() => (object) $userData);

            $shared['isAuthed'] = true;
            $shared['auth'] = [
                'token' => $jwt['token'],
                'user' => $userData,
            ];
        } catch (TokenExpiredException | TokenInvalidException | JWTException $e) {

            // stay guest (isAuthed = false)
        }

        return $shared;
    }
}
