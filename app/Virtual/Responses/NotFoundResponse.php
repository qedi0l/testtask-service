<?php

namespace App\Virtual\Responses;

use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

#[Response(
    response: 404, description: 'Не найдено',
    content: new JsonContent(
        properties: [
            new Property(
                property: 'message',
                title: 'Сообщение об ошибке',
                type: 'string',
                example: 'Сущность [Сущность][ID] не найдена'
            ),
        ]
    )
)]
class NotFoundResponse
{

}
