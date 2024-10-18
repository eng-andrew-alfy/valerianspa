<?php

    namespace App\Services;

    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;

    class TamaraService
    {
        protected $baseUrl;
        protected $apiKey;

        public function __construct()
        {
            $this->baseUrl = config('tamara.sandbox.base_url');
            $this->apiKey = config('tamara.sandbox.api_key');
        }

        public function createOrder(array $data)
        {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])->post($this->baseUrl . 'checkout', $data);

                // Check if the request was successful
                if ($response->successful()) {
                    return $response->json(); // Automatically decodes the JSON response
                } else {
                    // Log the response error and return null or an error message
                    Log::error('Tamara createOrder failed', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    return ['error' => 'Failed to create order'];
                }
            } catch (\Exception $e) {
                // Log the exception message
                Log::error('Tamara createOrder exception: ' . $e->getMessage());
                return ['error' => 'An error occurred while creating the order'];
            }
        }
    }
