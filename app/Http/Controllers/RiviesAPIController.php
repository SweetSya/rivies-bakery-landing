<?php

namespace App\Http\Controllers;

use App\useCheckJWT;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RiviesAPIController extends Controller
{
    use useCheckJWT;

    public $authenticated = null;
    protected $config;

    public function __construct()
    {
        $this->authenticated = $this->ensureAuthenticated();
        $this->config = $this->getAPIConfig();
    }
    // Get API configuration from environment variables
    public function getAPIConfig()
    {
        return [
            'url' => env('API_URL'),
            'key' => env('API_KEY'),
            'header' => env('API_HEADER_PREFIX'),
        ];
    }
    // Get the API headers including auth token if available
    public function getAPIHeader()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            $this->config['header'] => 'Bearer ' . $this->config['key'],
        ];
        if ($this->authenticated && $this->authenticated['auth']['token']) {
            $headers['Authorization'] = 'Bearer ' . $this->authenticated['auth']['token'];
        }
        return $headers;
    }

    // Check for JWT in cookies and set auth state
    protected function ensureAuthenticated()
    {
        $shared = $this->checkJWTCookies();
        if (!$shared || !$shared['isAuthed']) {
            return null;
        }
        return  $shared; // Authenticated
    }
    // Make a GET request to the API
    public function apiGet($endpoint, $params = [], $headers = [], $aborting = true)
    {
        $client = Http::withHeaders(array_merge($this->getAPIHeader(), $headers));
        $response = $client->get($this->config['url'] . $endpoint, $params);
        if (!$response->ok()) {
            $error = [
                'status' => $response->status(),
                'message' => $response->json()['message'] ?? 'Error occurred',

            ];
            $error_code = time() . '-' . rand(1000, 9999);
            Log::error('API GET Error [' . $error_code . ']: ' . json_encode($error));
            if ($aborting) {
                abort(500, $error_code);
            }
            // return $error;
        }
        return $response;
    }
    public function apiPost($endpoint, $params = [], $headers = [], $aborting = true)
    {
        // Check if there are any file uploads in the params
        $hasFiles = false;
        foreach ($params as $value) {
            if ($value instanceof UploadedFile) {
                $hasFiles = true;
                break;
            }
        }

        // Use appropriate headers based on whether files are present
        $client = Http::withHeaders(array_merge($this->getAPIHeader(!$hasFiles), $headers));

        // If there are files, handle them separately
        if ($hasFiles) {
            foreach ($params as $key => $value) {
                if ($value instanceof UploadedFile) {
                    $client = $client->attach($key, fopen($value->getPathname(), 'r'), $value->getClientOriginalName());
                    unset($params[$key]); // Remove file from regular data
                }
            }
        }

        $response = $client->post($this->config['url'] . $endpoint, $params);

        if (!$response->ok()) {
            $error = [
                'status' => $response->status(),
                'message' => $response->json()['message'] ?? 'Error occurred',
            ];
            $error_code = time() . '-' . rand(1000, 9999);
            Log::error('API POST Error [' . $error_code . ']: ' . json_encode($error));
            if ($aborting) {
                abort(500, $error_code);
            }
        }
        return $response;
    }
}
