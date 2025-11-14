<?php

namespace App\Services\Clients;

use Exception;
use Illuminate\Support\Facades\Http;

class ShippingServiceClient
{
    public function createFulfillmentOrder(array $data): array
    {
        $response = Http::baseUrl(config('services.amazon.seller_endpoint'))
            ->withHeaders([
                'x-amz-access-token' => config('services.amazon.x-amz-access-token')
            ])
            ->post(config('services.amazon.fba_out').'/fulfillmentOrders', $data);

        $result = json_decode($response->body(), 1);

        if (!$response->ok()) {
            throw new Exception('Something went wrong. Error: ' . $result['errors'][0]['code'] . '. Message: ' . $result['errors'][0]['message']);
        }

        return $result;
    }

    public function getFulfillmentOrder(string $sellerFulfillmentOrderId): array
    {
        $response = Http::baseUrl(config('services.amazon.seller_endpoint'))
            ->withHeaders([
                'x-amz-access-token' => config('services.amazon.x-amz-access-token')
            ])
            ->get(config('services.amazon.fba_out').'/fulfillmentOrders/'.$sellerFulfillmentOrderId);

        $result = json_decode($response->body(), 1);

        if (!$response->ok()) {
            throw new Exception('Something went wrong. Error: ' . $result['errors'][0]['code'] . '. Message: ' . $result['errors'][0]['message']);
        }

        return $result;
    }

    public function getPackageTrackingDetails(string $amazonFulfillmentTrackingNumber, int $packageNumber): array
    {
        $response = Http::baseUrl(config('services.amazon.seller_endpoint'))
            ->withHeaders([
                'x-amz-access-token' => config('services.amazon.x-amz-access-token')
            ])
            ->get(config('services.amazon.fba_out').'/tracking', [
                'packageNumber' => $packageNumber,
                'amazonFulfillmentTrackingNumber' => $amazonFulfillmentTrackingNumber
            ]);

        $result = json_decode($response->body(), 1);

        if (!$response->ok()) {
            throw new Exception('Something went wrong. Error: ' . $result['errors'][0]['code'] . '. Message: ' . $result['errors'][0]['message']);
        }

        return $result;
    }

}
