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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api) {
  // general information
  $api->get('/', function() {
    return [
      'API_NAME' => getenv('API_NAME'),
      'API_VERSION' => getenv('API_VERSION'),
      'API_STANDARDS_TREE' => getenv('API_STANDARDS_TREE'),
      'API_CONDITIONAL_REQUEST' => getenv('API_CONDITIONAL_REQUEST'),
      'API_STRICT' => getenv('API_STRICT'),
      'API_DEBUG' => getenv('API_DEBUG')
    ];
  });

  // authentication
  $api->post('authenticate', 'App\Api\V1\Controllers\AuthenticateController@authenticate');
});

$api->version('v1', ['middleware' => 'api.auth'], function($api) {
  // refresh token
  $api->get('refresh', 'App\Api\V1\Controllers\AuthenticateController@token');

  // authenticated user
  $api->get('user', function() {
    $user = app('Dingo\Api\Auth\Auth')->user();
    return $user;
  });
  
  // airs list end-point
  $api->get('/airs', 'App\Api\V1\Controllers\AirController@index');

  // sites list end-point
  $api->get('/sites', 'App\Api\V1\Controllers\SiteController@index');

});
