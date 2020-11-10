<?php

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
$router->get('welcome','UserController@welcome');
$router->group(['prefix' => 'api', 'middleware' => ['cors2', 'cors']], function () use ($router) {
    $router->post('/register', 'UserController@register');
    $router->post('/login', 'UserController@login');
    $router->post('/addplace','UserController@addPlace');
    $router->post('/addcity','UserController@addCity');
    $router->post('/forgetpassword', 'UserController@forgetPassword');




});

$router->group(['prefix' => 'api/user', 'middleware' => ['cors2', 'cors', 'UserAuth']], function () use ($router) {

    $router->post('/getallplaces','UserController@getAllPlaces');
    $router->post('/uploadphoto','UserController@uploadphoto');
    $router->post('/editprofile','UserController@editprofile');
    $router->post('/getphotos','UserController@getPhotosOfPlaces');
    $router->post('/showaplace','UserController@showAPlace');

});

