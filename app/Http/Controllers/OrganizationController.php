<?php

namespace App\Http\Controllers;

use App\DTO\issues\ClassifierDto;
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
use Illuminate\Http\Resources\Json\JsonResource;
use MatanYadaev\EloquentSpatial\Objects\Point;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Info;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Tag;

#[Info(
    version: '1.0.0',
    description: 'Swagger OpenApi documentation',
    title: 'service',
)]

#[Tag(name: 'service', description: 'service')]

#[Get(
    path: '/api/organisations', operationId: 'index',
    description: 'get', summary: '',
    tags: ['service'],
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
    tags: ['service'],
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
    tags: ['service'],
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
    tags: ['service'],
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
    tags: ['service'],
    parameters: [new Parameter(ref: '#/components/parameters/id')],
    responses: [
        new Response(ref: SuccessResponse::class, response: 200),
        new Response(ref: NotFoundResponse::class, response: 404),
    ]
)]
#[Get(
    path: '/api/organisations-by-building/{building_id}', operationId: 'organisations-by-building',
    description: 'Cписок всех организаций находящихся в конкретном здании', summary: '',
    tags: ['service'],
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
    tags: ['service'],
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
    tags: ['service'],
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
    tags: ['service'],
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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organization = Organization::query()
            ->with('building')
            ->simplePaginate(100);

        return OrganizationResource::collection($organization);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): OrganizationResource
    {
        $data = $request->validate([
            'name' => 'required|string',
            'building_id' => 'nullable|integer',
        ]);

        $organization = Organization::create($data);

        return OrganizationResource::make($organization);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $organization = Organization::query()
            ->with('building')
            ->findOrFail($id);

        return OrganizationResource::make($organization);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $organization = Organization::findOrFail($id);
        $data = $request->validate([
            'name' => 'string',
            'description' => 'nullable|string',
        ]);

        $organization->update($data);
        return OrganizationResource::make($organization);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $organization = Organization::findOrFail($id);
        $organization->delete();
        return response()->noContent();
    }

    public function getOrganisationsByBuildingId(int $building_id): JsonResource
    {
        $result = Organization::query()
            ->where('building_id',$building_id)
            ->simplePaginate(50);

        return OrganizationResource::collection($result);
    }

    public function getOrganisationByActivityId(int $activity_id): JsonResource
    {
        $result = Organization::query()
            ->select('organizations.*')
            ->leftjoin('organization_activities','organizations.id','=','organization_activities.organization_id')
            ->where('organization_activities.activity_id', $activity_id)
            ->simplePaginate(50);

        return OrganizationResource::collection($result);
    }

    public function getOrganisationsNearPoint(NearOrganisationsRequest $request): JsonResource
    {
        $data = $request->validated();
        $point = new Point($data['latitude'], $data['longitude'],'4326');

        $result = Building::query()
            ->whereDistance('location',$point,'<=', $data['distance'])
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

