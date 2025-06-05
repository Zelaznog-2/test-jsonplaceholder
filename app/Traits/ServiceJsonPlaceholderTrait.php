<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;


trait ServiceJsonPlaceholderTrait
{
    /**
     * Get the base URL for the JSON Placeholder API.
     *
     * @return string
     */
    private function getBaseUrl(): string
    {
        return env('JSON_PLACEHOLDER_API_URL', 'https://jsonplaceholder.typicode.com');
    }

    /**
     * Get the full URL for a given endpoint.
     *
     * @param string $endpoint
     * @return string
     */
    private function getUrl(string $endpoint): string
    {
        return $this->getBaseUrl() . '/' . ltrim($endpoint, '/');
    }

    /**
     * Fetch data from the JSON Placeholder API.
     *
     * @param string $endpoint
     * @return array
     */
    public function fetchDataFromApi(string $endpoint): array
    {
        try {
            $url = $this->getUrl($endpoint);
            $response = Http::get($url);
            if ($response->successful()) {
                return [
                    'data' => $response->json(),
                    'status' => $response->status(),
                    'message' => 'Data fetched successfully',
                ];
            }

            return [
                'error' => 'Failed to fetch data from API',
                'status' => $response->status(),
                'message' => $response->body(),
            ];
        } catch (\Throwable $th) {
            return [
                'error' => 'An error occurred while fetching data',
                'status' => 500,
                'message' => $th->getMessage(),
            ];
        }
    }


    /**
     * Create data in the JSON Placeholder API.
     *
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public function createDataFromApi(string $endpoint, array $data) : array {
        try {
            $url = $this->getUrl($endpoint);
            $response = Http::post($url, $data);
            if ($response->successful()) {
                return [
                    'data' => $response->json(),
                    'status' => $response->status(),
                    'message' => 'Data updated successfully',
                ];
            }

            return [
                'error' => 'Failed to update data in API',
                'status' => $response->status(),
                'message' => $response->body(),
            ];
        } catch (\Throwable $th) {
            return [
                'error' => 'An error occurred while updating data',
                'status' => 500,
                'message' => $th->getMessage(),
            ];
        }
    }


    /**
     * Update data in the JSON Placeholder API.
     *
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public function updateDateFromApi(string $endpoint, array $data): array
    {
        try {
            $url = $this->getUrl($endpoint);
            $response = Http::put($url, $data);
            if ($response->successful()) {
                return [
                    'data' => $response->json(),
                    'status' => $response->status(),
                    'message' => 'Data updated successfully',
                ];
            }

            return [
                'error' => 'Failed to update data in API',
                'status' => $response->status(),
                'message' => $response->body(),
            ];
        } catch (\Throwable $th) {
            return [
                'error' => 'An error occurred while updating data',
                'status' => 500,
                'message' => $th->getMessage(),
            ];
        }
    }
}