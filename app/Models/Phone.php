<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'Phone',
    title: 'Model. Phone',
    description: "Phone model",
    required: ['phone'],
    properties: [
        new Property(
            property: 'phone',
            title: 'phone',
            type: 'string',
            example: 'text',
        ),
    ]
)]
class Phone extends Model
{
    use HasFactory;

    protected $table = 'phones';

    protected $fillable = [
        'phone',
    ];

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organizations_phones');
    }
}
