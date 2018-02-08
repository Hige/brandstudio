<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->resources([
        'events'                 => EventController::class,
        'concert-halls'          => ConcertHallController::class,
        'subscriptions'          => SubscriptionUserController::class,
    ]);

    $router->post('events/release', 'EventController@release');
    $router->post('events/restore', 'EventController@restore');

    $router->post('concert-halls/release', 'ConcertHallController@release');
    $router->post('concert-halls/restore', 'ConcertHallController@restore');
});
