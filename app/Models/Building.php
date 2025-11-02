<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'Building',
    title: 'Model. Building',
    description: "Building model",
    required: ['address','location'],
    properties: [
        new Property(
            property: 'address',
            title: 'address',
            type: 'string',
            example: 'text',
        ),
        new Property(
            property: 'location',
            title: 'location',
            type: 'string',
            example: 'POINT(-71.064544 42.28787)',
        ),
    ]
)]
class Building extends Model
{
    use HasFactory;
    use HasSpatial;

    public $timestamps = false;

    protected $fillable = [
        'address',
        'location',
    ];

    protected $spatialFields = [
        'location',
    ];

    protected $casts = [
        'location' => Point::class,
    ];
}
