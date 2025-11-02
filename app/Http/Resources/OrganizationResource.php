<?php

namespace App\Http\Resources;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'OrganizationResource',
    title: 'OrganizationResource',
    properties: [
        new Property(
            property: 'data',
            title: 'data',
            type: 'array',
            items: new Items(ref: OrganizationResource::class)
        )
    ],
)]#


/** @mixin Organization */
class OrganizationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? $this['id'],
            'name' => $this->name ?? $this['name'],
            'building_id' => $this->building_id ?? $this['building_id'],
            'building' => $this['building_id'] ? null : $this->whenLoaded('building'),
            'created_at' => $this->created_at ?? $this['created_at'],
            'updated_at' => $this->updated_at ?? $this['updated_at'],
        ];
    }
}
