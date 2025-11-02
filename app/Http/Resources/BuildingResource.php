<?php

namespace App\Http\Resources;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'BuildingResource',
    title: 'BuildingResource',
    properties: [
        new Property(
            property: 'data',
            title: 'data',
            type: 'array',
            items: new Items(ref: BuildingResource::class)
        )
    ],
)]#
/** @mixin Building */
class BuildingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'address' => $this->address,
            'location' => $this->location,
        ];
    }
}
