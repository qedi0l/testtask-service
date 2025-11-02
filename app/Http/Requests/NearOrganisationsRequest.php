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
            new Property(property: 'latitude', type: 'integer', maximum: 90, minimum: -90,example: -45.54332),
            new Property(property: 'longitude', type: 'integer', maximum: 180, minimum: -180, example: 65.32354),
            new Property(property: 'distance', type: 'integer', example: 500),
        ]
    )
)]
class NearOrganisationsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'latitude' => ['required'],
            'longitude' => ['required'],
            'distance' => ['required'],
        ];
    }
}
