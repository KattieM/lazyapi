<?php

use Illuminate\Http\Request;

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
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('changeusername', 'AccountController@changeUsername');
    Route::post('changepassword', 'AccountController@changePassword');
    Route::get('home', 'HomeController@returnEventsAndProjects');
    Route::post('events', 'EventsController@saveEvent');
    Route::get('events/{id}', 'EventsController@showEventDetails');
    Route::delete('events', 'EventsController@deleteEvent');
    Route::get('events', 'EventsController@showDetails');
    Route::get('projects', 'ProjectsController@showDetails');
    Route::get('projects/{id}', 'ProjectsController@showProjectDetails');
    Route::post('projects', 'ProjectsController@saveProject');
    Route::delete('projects', 'ProjectsController@deleteProject');
});

