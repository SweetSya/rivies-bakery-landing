<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationController extends RiviesAPIController
{
    public function view(Request $request)
    {
        if ($request->cookie('session_token')) {
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
                return Inertia::render('Login');
            }
            return redirect()->route('account-settings.information')->withErrors([
                'warn' => 'Kamu sudah masuk sebagai ' . ($shared['auth']['user']['name'] ?? 'Pengguna')
            ]);
        }
        return Inertia::render('Login');
    }

    public function login(Request $request)
    {
        // Validate the request data
        if ($request->missing(['email', 'password'])) {
            return response()->json(['error' => 'Missing credentials'], 400);
        }

        $response = $this->apiPost('login', $request->only('email', 'password'), aborting: false);
        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['token'])) {
                return response()->json(['message' => 'Login success'])
                    ->cookie(
                        'session_token',
                        json_encode([
                            'user' => $data['user'],
                            'token' => $data['token'],
                        ]),
                        auth()->guard(env('API_APP', 'bakery-store'))->factory()->getTTL(), // minutes
                        '/',
                        null,
                        true,  // Secure (only HTTPS)
                        true   // HttpOnly
                    );
            }
        }
        return response()->json(['error' => $data['message'] ?? 'Login failed'], $response->status());
    }
    public function logout(Request $request)
    {
        // Clear the session cookie
        $this->apiPost('logout', [], [], false);
        return redirect()->route('login')
            ->withErrors(
                [
                    'success' => 'Berhasil keluar dari akun'
                ]
            )
            ->cookie('session_token', '', -1, '/', null, true, true);
    }
}
