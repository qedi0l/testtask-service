<?php

namespace App\Services;

use App\Data\AbstractOrder;
use App\Enums\ShippingSpeedCategoriesEnum;
use App\Contracts\BuyerInterface;
use App\Contracts\ShippingServiceInterface;
use App\Services\Clients\ShippingServiceClient;
use PHPUnit\Framework\Exception;

class ShippingService implements ShippingServiceInterface
{
    public function ship(AbstractOrder $order, BuyerInterface $buyer): string
    {
        $this->createFulfillmentOrder($order);
        $fulfilment = $this->getFulfillmentOrder($order->data['order_id']);

        $amazonFulfillmentTrackingNumber = $fulfilment['amazonFulfillmentTrackingNumber'];
        $packageNumber = $fulfilment['packageNumber'];

        $getPackageTrackingDetails = $this->getPackageTrackingDetails($amazonFulfillmentTrackingNumber, $packageNumber);

        $trackingNumber = $getPackageTrackingDetails['trackingNumber'];

        return $trackingNumber;
    }

    private function createFulfillmentOrder(AbstractOrder $order): void
    {
        $data = [
            'sellerFulfillmentOrderId' => $order->data['order_id'],
            'displayableOrderId' => $order->data['order_unique'],
            'displayableOrderDate' => $order->data['order_date'],
            'displayableOrderComment' => $order->data['comments'],
            'shippingSpeedCategory' => ShippingSpeedCategoriesEnum::from($order->data['shipping_type_id'])->name,
            'destinationAddress' => $order->data['shipping_adress'],
            'items' => array_map(static function ($item) {
                return [
                    'sellerSku' => $item['sku'],
                    'sellerFulfillmentOrderItemId' => $item['order_id'],
                    'quantity' => $item['ammount']
                ];
            },$order->data['products']) ,
        ];

        $client = new ShippingServiceClient();

        try {
            $client->createFulfillmentOrder($data);
        } catch (\Throwable $exception) {
            throw new Exception($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    private function getFulfillmentOrder(string $sellerFulfillmentOrderId): array
    {
        $client = new ShippingServiceClient();

        try {
            $result = $client->getFulfillmentOrder($sellerFulfillmentOrderId);
        } catch (\Throwable $exception) {
            throw new Exception($exception->getMessage(), $exception->getCode(), $exception);
        }

        return $result;
    }

    private function getPackageTrackingDetails(string $amazonFulfillmentTrackingNumber, int $packageNumber): array
    {
        $client = new ShippingServiceClient();

        try {
            $result = $client->getPackageTrackingDetails($amazonFulfillmentTrackingNumber, $packageNumber);
        } catch (\Throwable $exception) {
            throw new Exception($exception->getMessage(), $exception->getCode(), $exception);
        }

        return $result;
    }
}
