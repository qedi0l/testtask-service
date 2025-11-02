<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'Activity',
    title: 'Model. Activity',
    description: "Activity model",
    required: ['name'],
    properties: [
        new Property(
            property: 'name',
            title: 'name',
            type: 'string',
            example: 'text',
        ),
        new Property(
            property: 'relatedActivities',
            ref: Activity::class,
        ),
        new Property(
            property: 'activities',
            ref: Activity::class,
        ),
        new Property(
            property: 'organizations',
            ref: Organization::class,
        ),
    ]
)]
class Activity extends Model
{
    use HasFactory;

    protected $table = 'activities';
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function relatedActivities()
    {
        return $this->morphToMany(Activity::class, 'activity', 'activity_activity','related_activity_id');
    }

    public function activities()
    {
        return $this->morphedByMany(Activity::class, 'activity', 'activity_activity','activity_id');
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class,'organization_activities');
    }

}
