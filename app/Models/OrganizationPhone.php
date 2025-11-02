<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'OrganizationPhone',
    title: 'Model. OrganizationPhone',
    description: "OrganizationPhone model",
    required: ['phone','organization_id'],
    properties: [
        new Property(
            property: 'phone',
            title: 'phone',
            type: 'string',
            example: 'text',
        ),
        new Property(
            property: 'organization_id',
            ref: Organization::class,
        ),
    ]
)]
class OrganizationPhone extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'organization_phones';

    protected $fillable = [
        'phone',
        'organization_id',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
