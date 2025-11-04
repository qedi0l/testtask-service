<?php

namespace App\Http\Controllers;

use App\Http\Requests\NearOrganisationsRequest;
use App\Http\Resources\BuildingResource;
use App\Http\Resources\OrganizationResource;
use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use App\Virtual\Responses\NotFoundResponse;
use App\Virtual\Responses\ServerErrorResponse;
use App\Virtual\Responses\SuccessResponse;
use App\Virtual\Responses\UnprocessableResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use MatanYadaev\EloquentSpatial\Objects\Point;
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
    path: '/api/organisations', operationId: 'index',
    description: 'get', summary: '',
    tags: ['organisations'],
    responses: [
        new Response(
            response: 200,
            description: 'Успешный ответ',
            content: new JsonContent(ref: '#/components/schemas/OrganizationResource'),
        ),
        new Response(ref: UnprocessableResponse::class, response: 422),
    ]
)]
#[Post(
    path: '/api/organisations', operationId: 'store',
    description: 'store', summary: '',
    requestBody: new RequestBody(content: new JsonContent(
        required: ['name', 'building_id'],
        properties: [
            new Property(property: 'name', ref: '#/components/schemas/Organization/properties/name', type: 'string', maxLength: 255, minLength: 1),
            new Property(property: 'building_id', ref: '#/components/schemas/Organization/properties/building_id', type: 'integer'),
        ]
    )),
    tags: ['organisations'],
    responses: [
        new Response(
            response: 200,
            description: 'Успешный ответ',
            content: new JsonContent(ref: '#/components/schemas/OrganizationResource'),
        ),
        new Response(ref: NotFoundResponse::class, response: 404),
    ]
)]
#[Get(
    path: '/api/organisations/{id}', operationId: 'show',
    description: 'show', summary: '',
    tags: ['organisations'],
    parameters: [new Parameter(ref: '#/components/parameters/id')],
    responses: [
        new Response(
            response: 201,
            description: 'Успешный ответ',
            content: new JsonContent(ref: '#/components/schemas/OrganizationResource'),
        ),
        new Response(ref: UnprocessableResponse::class, response: 422),
        new Response(ref: ServerErrorResponse::class, response: 500),
    ]
)]
#[Put(
    path: '/api/organisations/{id}', operationId: 'update',
    description: 'update', summary: '',
    tags: ['organisations'],
    parameters: [new Parameter(ref: '#/components/parameters/id')],
    responses: [
        new Response(
            response: 200,
            description: 'Успешный ответ',
            content: new JsonContent(ref: '#/components/schemas/OrganizationResource'),
        ),
        new Response(ref: NotFoundResponse::class, response: 404),
        new Response(ref: UnprocessableResponse::class, response: 422),
        new Response(ref: ServerErrorResponse::class, response: 500),
    ]
)]
#[Delete(
    path: '/api/organisations/{id}', operationId: 'delete',
    description: 'delete', summary: '',
    tags: ['organisations'],
    parameters: [new Parameter(ref: '#/components/parameters/id')],
    responses: [
        new Response(ref: SuccessResponse::class, response: 200),
        new Response(ref: NotFoundResponse::class, response: 404),
    ]
)]
#[Get(
    path: '/api/organisations-by-building/{building_id}', operationId: 'organisations-by-building',
    description: 'Cписок всех организаций находящихся в конкретном здании', summary: '',
    tags: ['organisations'],
    parameters: [new Parameter(ref: '#/components/parameters/building_id')],
    responses: [
        new Response(
            response: 201,
            description: 'Успешный ответ',
            content: new JsonContent(ref: '#/components/schemas/OrganizationResource'),
        ),
        new Response(ref: UnprocessableResponse::class, response: 422),
        new Response(ref: ServerErrorResponse::class, response: 500),
    ]
)]
#[Get(
    path: '/api/organisations-by-activity/{activity_id}', operationId: 'organisations-by-activity',
    description: 'Cписок всех организаций, которые относятся к указанному виду деятельности', summary: '',
    tags: ['organisations'],
    parameters: [new Parameter(ref: '#/components/parameters/activity_id')],
    responses: [
        new Response(
            response: 201,
            description: 'Успешный ответ',
            content: new JsonContent(ref: '#/components/schemas/OrganizationResource'),
        ),
        new Response(ref: UnprocessableResponse::class, response: 422),
        new Response(ref: ServerErrorResponse::class, response: 500),
    ]
)]
#[Get(
    path: '/api/organisations-by-activities/{activity_id}', operationId: 'organisations-by-activities',
    description: 'Искать организации по виду деятельности.', summary: '',
    tags: ['organisations'],
    parameters: [new Parameter(ref: '#/components/parameters/activity_id')],
    responses: [
        new Response(
            response: 201,
            description: 'Успешный ответ',
            content: new JsonContent(ref: '#/components/schemas/OrganizationResource'),
        ),
        new Response(ref: UnprocessableResponse::class, response: 422),
        new Response(ref: ServerErrorResponse::class, response: 500),
    ]
)]
#[Post(
    path: '/api/organisations-by-distance', operationId: 'organisations-by-distance',
    description: 'Cписок организаций, которые находятся в заданном радиусе/прямоугольной области относительно указанной точки на карте', summary: '',
    requestBody: new RequestBody(ref: NearOrganisationsRequest::class),
    tags: ['organisations'],
    responses: [
        new Response(
            response: 200,
            description: 'Успешный ответ',
            content: new JsonContent(ref: '#/components/schemas/BuildingResource'),
        ),
        new Response(ref: NotFoundResponse::class, response: 404),
    ]
)]
class OrganizationController extends Controller
{

    public function index(): ResourceCollection
    {
        $organization = Organization::query()
            ->with('building')
            ->simplePaginate(100);

        return OrganizationResource::collection($organization);
    }

    public function store(Request $request): OrganizationResource
    {
        $data = $request->validate([
            'name' => 'required|string',
            'building_id' => 'nullable|integer',
        ]);

        $organization = Organization::create($data);

        return OrganizationResource::make($organization);
    }

    public function show(int $id): OrganizationResource
    {
        $organization = Organization::query()
            ->with('building')
            ->findOrFail($id);

        return OrganizationResource::make($organization);
    }

    public function update(Request $request, int $id): OrganizationResource
    {
        $organization = Organization::findOrFail($id);
        $data = $request->validate([
            'name' => 'string',
            'building_id' => 'integer',
        ]);

        $organization->update($data);
        return OrganizationResource::make($organization);
    }

    public function destroy(int $id): \Illuminate\Http\Response
    {
        $organization = Organization::findOrFail($id);
        $organization->delete();
        return response()->noContent();
    }

    public function getOrganisationsByBuildingId(int $building_id): ResourceCollection
    {
        $result = Organization::query()
            ->where('building_id', $building_id)
            ->simplePaginate(50);

        return OrganizationResource::collection($result);
    }

    public function getOrganisationByActivityId(int $activity_id): ResourceCollection
    {
        $result = Organization::query()
            ->select('organizations.*')
            ->leftjoin('organization_activities', 'organizations.id', '=', 'organization_activities.organization_id')
            ->where('organization_activities.activity_id', $activity_id)
            ->simplePaginate(50);

        return OrganizationResource::collection($result);
    }

    public function getOrganisationsNearPoint(NearOrganisationsRequest $request): ResourceCollection
    {
        $data = $request->validated();
        $point = new Point($data['latitude'], $data['longitude'], '4326');

        $result = Building::query()
            ->whereDistance('location', $point, '<=', $data['distance'])
            ->simplePaginate(500);

        return BuildingResource::collection($result);
    }

    public function getOrganizationByActivityRecursively(int $activity_id)
    {
        $mainActivity = Activity::findOrFail($activity_id);
        $allOrganizations = [];
        $allOrganizations += $mainActivity->organizations()->get()->toArray();

        $depth = 0;
        function getRelatedOrganizations(Activity $activity, &$allOrganizations, &$depth){
            $activities = $activity->relatedActivities;
            if ($activities && ($depth <= 2)) {
                $depth++;
                foreach ($activities as $relatedActivity) {
                    $relatedOrg = $relatedActivity->organizations()->get()->toArray();
                    foreach ($relatedOrg as $org) {
                        if (!in_array($org, $allOrganizations)) {
                            $allOrganizations[] = $org;
                        }
                    }
                    getRelatedOrganizations($relatedActivity, $allOrganizations, $depth);
                }
            }
        }

        getRelatedOrganizations($mainActivity, $allOrganizations, $depth);

        return OrganizationResource::collection($allOrganizations);
    }
}

