<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\RequestBody;

#[RequestBody(
    content: new JsonContent(
        required: ['latitude', 'longitude', 'distance'],
        properties: [
            new Property(property: 'activity_id', type: 'integer',example: 1),
        ]
    )
)]
class AppendActivityRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'activity_id' => ['required'],
        ];
    }
}
