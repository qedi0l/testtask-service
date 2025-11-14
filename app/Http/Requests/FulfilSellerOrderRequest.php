<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\RequestBody;

#[RequestBody(
    content: new JsonContent(
        required: ['order_id'],
        properties: [
            new Property(property: 'order_id', type: 'integer', example: 1),
        ]
    )
)]
class FulfilSellerOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order_id' => ['required','integer'],
        ];
    }
}
