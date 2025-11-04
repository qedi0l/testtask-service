<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppendActivityRequest;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use App\Virtual\Responses\NotFoundResponse;
use App\Virtual\Responses\ServerErrorResponse;
use App\Virtual\Responses\SuccessResponse;
use App\Virtual\Responses\UnprocessableResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;

#[Get(
    path: '/api/activities', operationId: 'indexActivity',
    description: 'get', summary: '',
    tags: ['activities'],
    responses: [
        new Response(
            response: 200,
            description: 'Успешный ответ',
            content: new JsonContent(ref: '#/components/schemas/ActivityResource'),
        ),
        new Response(ref: UnprocessableResponse::class, response: 422),
    ]
)]
#[Post(
    path: '/api/activities', operationId: 'storeActivity',
    description: 'store', summary: '',
    requestBody: new RequestBody(content: new JsonContent(
        required: ['name', 'building_id'],
        properties: [
            new Property(property: 'name', ref: '#/components/schemas/Organization/properties/name', type: 'string', maxLength: 255, minLength: 1),
            new Property(property: 'building_id', ref: '#/components/schemas/Organization/properties/building_id', type: 'integer'),
        ]
    )),
    tags: ['activities'],
    responses: [
        new Response(
            response: 200,
            description: 'Успешный ответ',
            content: new JsonContent(ref: '#/components/schemas/ActivityResource'),
        ),
        new Response(ref: NotFoundResponse::class, response: 404),
    ]
)]
#[Get(
    path: '/api/activities/{id}', operationId: 'showActivity',
    description: 'show', summary: '',
    tags: ['activities'],
    parameters: [new Parameter(ref: '#/components/parameters/id')],
    responses: [
        new Response(
            response: 201,
            description: 'Успешный ответ',
            content: new JsonContent(ref: '#/components/schemas/ActivityResource'),
        ),
        new Response(ref: UnprocessableResponse::class, response: 422),
        new Response(ref: ServerErrorResponse::class, response: 500),
    ]
)]
#[Put(
    path: '/api/activities/{id}', operationId: 'updateActivity',
    description: 'update', summary: '',
    tags: ['activities'],
    parameters: [new Parameter(ref: '#/components/parameters/id')],
    responses: [
        new Response(
            response: 200,
            description: 'Успешный ответ',
            content: new JsonContent(ref: '#/components/schemas/ActivityResource'),
        ),
        new Response(ref: NotFoundResponse::class, response: 404),
        new Response(ref: UnprocessableResponse::class, response: 422),
        new Response(ref: ServerErrorResponse::class, response: 500),
    ]
)]
#[Delete(
    path: '/api/activities/{id}', operationId: 'deleteActivity',
    description: 'delete', summary: '',
    tags: ['activities'],
    parameters: [new Parameter(ref: '#/components/parameters/id')],
    responses: [
        new Response(ref: SuccessResponse::class, response: 200),
        new Response(ref: NotFoundResponse::class, response: 404),
    ]
)]
#[Post(
    path: '/api/append-activity/{id}', operationId: 'appendActivity',
    description: 'append child activity', summary: '',
    requestBody: new RequestBody(ref: AppendActivityRequest::class),
    tags: ['activities'],
    parameters: [new Parameter(ref: '#/components/parameters/id')],
    responses: [
        new Response(ref: SuccessResponse::class, response: 200),
        new Response(ref: NotFoundResponse::class, response: 400),
        new Response(ref: ServerErrorResponse::class, response: 403),
    ]
)]
class ActivityController extends Controller
{

    public function index(): ResourceCollection
    {
        $organization = Activity::query()
            ->simplePaginate(100);

        return ActivityResource::collection($organization);
    }

    public function store(Request $request): ActivityResource
    {
        $data = $request->validate([
            'name' => 'required|string',
        ]);

        $organization = Activity::create($data);

        return ActivityResource::make($organization);
    }

    public function show(int $id): ActivityResource
    {
        $organization = Activity::query()
            ->findOrFail($id);

        return ActivityResource::make($organization);
    }

    public function update(Request $request, int $id): ActivityResource
    {
        $activity = Activity::findOrFail($id);
        $data = $request->validate([
            'name' => 'string',
        ]);

        $activity->update($data);
        return ActivityResource::make($activity);
    }

    public function destroy(int $id): \Illuminate\Http\Response
    {
        $organization = Activity::findOrFail($id);
        $organization->delete();
        return response()->noContent();
    }

    public function appendActivity(Request $request, int $activityId)
    {
        $activity = Activity::findOrFail($activityId);

        if ($this->checkChildActivity($activity)){
            $data = $request->validate([
                'activity_id' => 'integer',
            ]);

            $activity->relatedActivities()->attach($data['activity_id']);

            return response()->noContent();
        }
        return response()->noContent(400);
    }


    private function checkChildActivity(Activity $mainActivity): bool
    {
        $allActivities = Collection::make([$mainActivity]);

        function getChildActivities(Activity $activity, &$allActivities)
        {
            foreach ($activity->relatedActivities as $relatedActivity) {
                if (!$allActivities->contains($relatedActivity)) {
                    $allActivities->push($relatedActivity);
                    getChildActivities($relatedActivity, $allActivities);
                }
            }
        }

        if ($allActivities->count() >= 3){
            return false;
        }

        getChildActivities($mainActivity, $allActivities);

        return true;
    }
}
