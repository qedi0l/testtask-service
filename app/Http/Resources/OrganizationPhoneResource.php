<?php

namespace App\Http\Resources;

use App\Models\OrganizationPhone;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin OrganizationPhone */
class OrganizationPhoneResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'phone' => $this->phone,

            'organization_id' => $this->organization_id,

            'organization' => new OrganizationResource($this->whenLoaded('organization')),
        ];
    }
}
