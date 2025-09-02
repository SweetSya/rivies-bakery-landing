<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait useAPIConfig
{
    protected $config;

    public function __construct()
    {
        $this->config = $this->getAPIConfig();
    }

    public function getAPIConfig()
    {
        return [
            'url' => env('API_URL'),
            'key' => env('API_KEY'),
            'header' => env('API_HEADER_PREFIX'),
        ];
    }
    public function getAPIHeader()
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            $this->config['header'] => 'Bearer ' . $this->config['key'],
        ];
    }
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
        $client = Http::withHeaders(array_merge($this->getAPIHeader(), $headers));
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
            // return $error;
        }
        return $response;
    }
}
