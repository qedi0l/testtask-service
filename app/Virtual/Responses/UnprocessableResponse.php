<?php

namespace App\Virtual\Responses;

use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Response;

#[Response(
    response: 422, description: 'Ошибка валидации',
    content: new JsonContent(
        properties: [
            new Property(
                property: 'message',
                title: 'Общее сообщение об ошибке',
                type: 'string',
                example: ''
            ),
            new Property(
                property: 'errors',
                title: 'Массив ошибок непрошедшего валидацию поля (опциональный)',
                properties: [
                    new Property(
                        property: 'value',
                        title: 'value',
                        type: 'string',
                        example: 'error'
                    )
                ],
                type: 'object'
            )
        ]
    )
)]
class UnprocessableResponse
{

}
