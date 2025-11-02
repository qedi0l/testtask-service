<?php

namespace App\Virtual\Responses;

use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

#[Response(
    response: 500, description: 'Серверная ошибка',
    content: new JsonContent(
        properties: [
            new Property(
                property: 'message',
                title: 'Общее сообщение об ошибке',
                type: 'string',
                example: 'При вычислении свойств объекта произошла ошибка'
            )
        ]
    )
)]
class ServerErrorResponse
{

}
