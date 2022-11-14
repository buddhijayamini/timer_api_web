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
    return "welcome to lumen API";
   // $router->app->version();
});

$router->get('/timer/create', function () {
    return view('index');
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {

    $router->get('timer',['uses' => 'TimerController@index']);
    $router->get('timer/view/{id}',['uses' => 'TimerController@show']);
    $router->get('timer/edit/{id}',['uses' => 'TimerController@edit']);
   // $router->get('timer/create',['uses' => 'TimerController@create']);
    $router->post('timer/save', ['as' => 'timer-save', 'uses' => 'TimerController@store']);
    $router->delete('timer/delete/{id}', ['uses' => 'TimerController@destroy']);


});
