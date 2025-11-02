<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'Organization',
    title: 'Model. Organization',
    description: "Organization model",
    required: ['name','building_id'],
    properties: [
        new Property(
            property: 'name',
            title: 'name',
            type: 'string',
            example: 'text',
        ),
        new Property(
            property: 'building_id',
            title: 'building_id',
            type: 'integer',
            example: 1,
        ),
    ]
)]
class Organization extends Model
{
    use HasFactory;

    protected $table = 'organizations';

    protected $fillable = [
        'name',
        'building_id',
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'organization_activities');
    }

    public function phones(): BelongsToMany
    {
        return $this->belongsToMany(Phone::class, 'organization_phones','');
    }
}
