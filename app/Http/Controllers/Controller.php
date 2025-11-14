<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes\Info;
use OpenApi\Attributes\Tag;

#[Info(
    version: '1.0.0',
    description: 'Swagger OpenApi documentation',
    title: 'service',
)]
#[Tag(name: 'amazonFBA', description: 'amazonFBA')]
#[Tag(name: 'activities', description: 'activities')]
#[Tag(name: 'organizations', description: 'organizations')]

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
