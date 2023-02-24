<?php

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
//
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('search/taxon', 'SearchController@searchTaxon');
Route::get('organization-type', 'PublisherController@getOrganizationType');
Route::get('resource-type', 'PublisherController@getResourceType');
Route::get('protectedareas/{id}', 'ProtectedAreaController@detail');

Route::get('protectedareas/{id}/images', 'ProtectedAreaController@indexImage');
