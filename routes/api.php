<?php

use App\Http\Controllers\OrganizationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'token:api-base'], function() {

    Route::get('organisations', [OrganizationController::class, 'index']);
    Route::post('organisations', [OrganizationController::class, 'store']);
    Route::get('organisations/{id}', [OrganizationController::class, 'show'])->whereNumber('id');
    Route::put('organisations/{id}', [OrganizationController::class, 'update'])->whereNumber('id');
    Route::delete('organisations/{id}', [OrganizationController::class, 'destroy'])->whereNumber('id');

    Route::get('organisations-by-building/{building_id}', [OrganizationController::class, 'getOrganisationsByBuildingId'])->whereNumber('building_id');
    Route::get('organisations-by-activity/{activity_id}', [OrganizationController::class, 'getOrganisationByActivityId'])->whereNumber('activity_id');
    Route::get('organisations-by-activities/{activity_id}', [OrganizationController::class, 'getOrganizationByActivityRecursively'])->whereNumber('activity_id');
    Route::post('organisations-by-distance', [OrganizationController::class, 'getOrganisationsNearPoint']);
});

