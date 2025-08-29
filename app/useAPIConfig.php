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
    public function apiGet($endpoint, $params = [])
    {
        $client = Http::withHeaders($this->getAPIHeader());
        $response = $client->get($this->config['url'] . $endpoint, $params);
        if (!$response->ok()) {
            $error = [
                'status' => $response->status(),
                'message' => $response->json()['message'] ?? 'Error occurred',
            ];
            $error_code = time() . '-' . rand(1000, 9999);
            Log::error('API GET Error [' . $error_code . ']: ' . json_encode($error));
            abort(500, $error_code);
            // return $error;
        }
        return $response;
    }
}
