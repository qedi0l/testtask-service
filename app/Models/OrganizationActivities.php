<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'OrganizationActivities',
    title: 'Model. OrganizationActivities',
    description: "OrganizationActivities model",
    required: ['organization_id','activity_id'],
    properties: [
        new Property(
            property: 'name',
            title: 'name',
            type: 'string',
            example: 'text',
        ),
        new Property(
            property: 'organization_id',
            ref: Organization::class,
        ),
        new Property(
            property: 'activity_id',
            ref: Activity::class,
        ),
    ]
)]
class OrganizationActivities extends Model
{
    public $timestamps = false;

    protected $table = 'organization_activities';

    protected $fillable = [
        'organization_id',
        'activity_id',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}
