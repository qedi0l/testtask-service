<?php

namespace App\Http\Controllers;

use App\Data\Buyer;
use App\Data\ConcreteAbstractOrder;
use App\Http\Requests\FulfilSellerOrderRequest;
use App\Services\ShippingService;
use App\Virtual\Responses\NotFoundResponse;
use App\Virtual\Responses\ServerErrorResponse;
use App\Virtual\Responses\SuccessResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;

#[Post(
    path: '/api/fulfill-seller-order', operationId: 'fulfillOrder',
    description: 'fulfillOrder', summary: '',
    requestBody: new RequestBody(ref: FulfilSellerOrderRequest::class),
    tags: ['amazonFBA'],
    parameters: [new Parameter(ref: '#/components/parameters/id')],
    responses: [
        new Response(ref: SuccessResponse::class, response: 200),
        new Response(ref: NotFoundResponse::class, response: 400),
        new Response(ref: ServerErrorResponse::class, response: 403),
    ]
)]
class ShippingController extends Controller
{
    public function fulfillSellerOrder(FulfilSellerOrderRequest $request): JsonResponse
    {
        $orderId = $request->validated()['order_id'];

        $order = new ConcreteAbstractOrder($orderId);
        $order->load();

        $buyerData = json_decode(file_get_contents(storage_path('app/mock/buyer.29664.json')), true) ?? [];
        $buyer = new Buyer($buyerData);

        $service = resolve(ShippingService::class);
        $trackingCode = $service->ship($order, $buyer);

        return response()->json(['tracking_code' => $trackingCode]);
    }
}
