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
    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $response = $this->apiPost('login', $request->only('email', 'password'), aborting: false);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['access_token'])) {
                // Convert expires_at timestamp to minutes for cookie expiration
                $expiresInMinutes = ($data['expires_at'] - time()) / 60;

                return response()->json([
                    'status' => 'success',
                    'message' => 'Login berhasil',
                    'user' => $data['user']
                ])->cookie(
                    'session_token',
                    json_encode([
                        'user' => $data['user'],
                        'token' => $data['access_token'],
                        'expires_at' => $data['expires_at'],
                        'expires_in' => $data['expires_in'],
                    ]),
                    $expiresInMinutes, // Cookie expires in minutes
                    '/',
                    null,
                    request()->secure(), // Secure only on HTTPS
                    true   // HttpOnly
                );
            }
        }

        $errorData = $response->json();
        return response()->json([
            'error' => $errorData['message'] ?? 'Login gagal',
            'errors' => $errorData['errors'] ?? []
        ], $response->status());
    }

    public function refresh(Request $request)
    {
        // Get current token from cookie
        $sessionCookie = $request->cookie('session_token');

        if (!$sessionCookie) {
            return response()->json(['error' => 'No session found'], 401);
        }

        try {
            $sessionData = json_decode($sessionCookie, true);

            if (!$sessionData || !isset($sessionData['token'])) {
                return response()->json(['error' => 'Invalid session'], 401);
            }
            // Send refresh request to API
            $response = $this->apiPost('refresh-token', [
                'token' => $sessionData['token']
            ], aborting: false);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['access_token'])) {
                    // Convert expires_at timestamp to minutes for cookie expiration
                    $expiresInMinutes = ($data['expires_at'] - time()) / 60;

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Token refreshed successfully',
                    ])->cookie(
                        'session_token',
                        json_encode([
                            'user' => $sessionData['user'],
                            'token' => $data['access_token'],
                            'expires_at' => $data['expires_at'],
                            'expires_in' => $data['expires_in'],
                        ]),
                        $expiresInMinutes,
                        '/',
                        null,
                        request()->secure(),
                        true
                    );
                }
            }

            $errorData = $response->json();
            return response()->json([
                'error' => $errorData['message'] ?? 'Token refresh failed'
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Session parsing failed'], 500);
        }
    }

    public function view(Request $request)
    {
        if ($request->cookie('session_token')) {
            try {
                // Grab token from cookie
                $jwt = json_decode($request->cookie('session_token'), true);

                if (!$jwt || !isset($jwt['token'])) {
                    throw new JWTException('Token missing');
                }

                // Check if token is expired
                if (isset($jwt['expires_at']) && $jwt['expires_at'] < time()) {
                    // Token expired, try to refresh
                    return redirect()->route('auth.refresh');
                }

                // Parse token & validate (no DB lookup)
                $payload = JWTAuth::setToken($jwt['token'])->getPayload();

                // Extract your stored claims
                $userData = $jwt['user'];

                // Fake user object so $request->user() works
                $request->setUserResolver(fn() => (object) $userData);

                return redirect()->route('account-settings.information')->withErrors([
                    'warn' => 'Kamu sudah masuk sebagai ' . ($shared['auth']['user']['name'] ?? 'Pengguna')
                ]);
            } catch (TokenExpiredException $e) {
                // Token expired, redirect to refresh
                return redirect()->route('auth.refresh');
            } catch (TokenInvalidException | JWTException $e) {
                // Invalid token, clear cookie and show login
                return redirect()->route('login')
                    ->withErrors(['error' => 'Sesi tidak valid, silakan login ulang'])
                    ->cookie('session_token', '', -1, '/', null, request()->secure(), true);
            }
        }

        return Inertia::render('Login');
    }

    public function logout(Request $request)
    {
        // Get token from cookie for API logout
        $sessionCookie = $request->cookie('session_token');
        $headers = [];

        if ($sessionCookie) {
            $sessionData = json_decode($sessionCookie, true);
            if ($sessionData && isset($sessionData['token'])) {
                $headers['Authorization'] = 'Bearer ' . $sessionData['token'];
            }
        }

        // Call API logout
        $this->apiPost('logout', [], $headers, false);

        // Clear the session cookie and redirect
        return redirect()->route('login')
            ->withErrors(['success' => 'Berhasil keluar dari akun'])
            ->cookie('session_token', '', -1, '/', null, request()->secure(), true);
    }

    public function register_view(Request $request)
    {
        return Inertia::render('Register');
    }

    public function register(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone_number' => 'required|string|max:20',
        ]);

        $data = $request->only(['name', 'email', 'password', 'phone_number']);

        $response = $this->apiPost('register', $data, aborting: false);

        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Registrasi berhasil, silakan login'
            ]);
        } else {
            $errorData = $response->json();
            return response()->json([
                'error' => $errorData['message'] ?? 'Registrasi gagal',
                'errors' => $errorData['errors'] ?? []
            ], $response->status());
        }
    }

    /**
     * Check if current session is valid
     */
    public function checkSession(Request $request)
    {
        $sessionCookie = $request->cookie('session_token');

        if (!$sessionCookie) {
            return response()->json(['valid' => false, 'message' => 'No session'], 401);
        }

        try {
            $sessionData = json_decode($sessionCookie, true);

            if (!$sessionData || !isset($sessionData['token'])) {
                return response()->json(['valid' => false, 'message' => 'Invalid session'], 401);
            }

            // Check if token is expired
            if (isset($sessionData['expires_at']) && $sessionData['expires_at'] < time()) {
                return response()->json(['valid' => false, 'message' => 'Session expired'], 401);
            }

            // Validate token with JWT
            $payload = JWTAuth::setToken($sessionData['token'])->getPayload();

            return response()->json([
                'valid' => true,
                'user' => $sessionData['user'],
                'expires_at' => $sessionData['expires_at'],
                'expires_in' => $sessionData['expires_at'] - time()
            ]);
        } catch (TokenExpiredException $e) {
            return response()->json(['valid' => false, 'message' => 'Token expired'], 401);
        } catch (TokenInvalidException | JWTException $e) {
            return response()->json(['valid' => false, 'message' => 'Invalid token'], 401);
        } catch (\Exception $e) {
            return response()->json(['valid' => false, 'message' => 'Session error'], 500);
        }
    }
}
