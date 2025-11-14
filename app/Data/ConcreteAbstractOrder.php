<?php

namespace App\Data;

use App\Models\AmazonOrder;

class ConcreteAbstractOrder extends AbstractOrder
{
    protected function loadOrderData(int $id): array
    {
        // imagine here db or another data source
        $jsonPath = storage_path('app/mock/order.16400.json');
        if (file_exists($jsonPath)) {
            return json_decode(file_get_contents($jsonPath), true);
        }
        return [];
    }
}
