<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'v1/'], function ($router) {
    $router->get('scores/','ScoreController@getAllScores');
    $router->post('place/score/','ScoreController@postScore');
    $router->put('place/score/token/{token}','ScoreController@confirmScore');

    $router->get('places/nearby/','PlacesController@getNearBy');
    $router->get('places/find/','PlacesController@findPlaces');

    $router->get('cities/','CityController@getCities');

    $router->get('email/test/','EmailController@getTestEmail');

});
