<?php

namespace App\Enums;

enum ShippingSpeedCategoriesEnum: string
{
    case Standard = '1';
    case Expedited = '2';
    case Priority = '3';
    case ScheduledDelivery = '4';

}
