<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'v1',
    'namespace' => 'API\V1',
    'as' => 'api.',
], function () {
    /*
    POST to api/v1/properties => Create a property.
    */
    Route::resource(
        'properties',
        'PropertyController',
        [
            'only' => ['store']
        ]
    );
    /*
    GET api/v1/properties/{guid}/analytics => Get all analytics of a property.
    PUT api/v1/properties/{guid}/analytics/{analyticid} => Add/update an analytic for a property.
    */
    Route::resource(
        'properties.analytics',
        'PropertyAnalyticController',
        [
            'only' => [
                'index',
                'update'
            ]
        ]
    );
    /*
    GET api/v1/report/properties/analytics => Get a report summary of all analytics.
    */
    Route::resource(
        'report/properties/analytics',
        'PropertyAnalyticsReportController',
        [
            'only' => ['index']
        ]
    );
});

Route::fallback(function () {
    return response()->json(['message' => 'API endpoint does not exist yet.'], 404);
})->name('api.404');
