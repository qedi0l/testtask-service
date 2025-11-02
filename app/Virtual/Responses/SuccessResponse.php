<?php

namespace App\Virtual\Responses;

use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

#[Response(
    response: 200, description: 'Успешный ответ',
    content: new JsonContent(
        properties: [
            new Property(
                property: 'success',
                title: 'Результат выполнения запроса',
                type: 'boolean',
                example: true
            )
        ]
    )
)]
class SuccessResponse
{

}
