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
    Route::get('home', 'HomeController@returnEventsAndProjects');
    Route::post('login', 'AuthController@login');

    Route::post('logout', 'AuthController@logout');

    Route::post('change-username', 'AccountController@changeUsername');
    Route::post('change-password', 'AccountController@changePassword');

    Route::get('users', 'UserController@showDetails');
    Route::get('users/{id}', 'UserController@showProfile');
    Route::post('users', 'UserController@saveUser');
    Route::delete('users', 'UserController@deleteUser');
    Route::put('upload-photo', 'UserController@uploadProfileImage');

    Route::get('events', 'EventsController@showDetails');
    Route::get('events/{id}', 'EventsController@showEventDetails');
    Route::post('events', 'EventsController@saveEvent');
    Route::delete('events', 'EventsController@deleteEvent');

    Route::get('projects', 'ProjectsController@showDetails');
    Route::get('projects/{id}', 'ProjectsController@showProjectDetails');
    Route::post('projects', 'ProjectsController@saveProject');
    Route::delete('projects', 'ProjectsController@deleteProject');

});

Route::get('/kaca', function(){
    return 'test';
});

