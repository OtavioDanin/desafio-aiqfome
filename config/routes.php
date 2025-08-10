<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;
use Modules\Clients\UI\Controllers\ClientController;
use Modules\Common\Application\JwtAuthMiddleware;
use Modules\Common\UI\AuthenticationController;
use Modules\Favorites\UI\FavoriteController;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'Modules\Clients\UI\Controllers\IndexController@index');

Router::get('/api/auth/generate', [AuthenticationController::class, 'generate']);

Router::addGroup('/api', function () {
    Router::addGroup('/clients', function () {
        Router::get('', [ClientController::class, 'index']);
        Router::post('', [ClientController::class, 'store']);
        Router::get('/{id}', [ClientController::class, 'show']);
        Router::put('/{id}', [ClientController::class, 'update']);
        Router::delete('/{id}', [ClientController::class, 'destroy']);
    });

    Router::addGroup('/favorites', function () {
        Router::post('', [FavoriteController::class, 'store']);
        Router::delete('/{id}', [FavoriteController::class, 'destroy']);
        //TO DO:  Router::get('', [FavoriteController::class, 'index']);
    });
},  ['middleware' => [JwtAuthMiddleware::class]]);

Router::get('/favicon.ico', function () {
    return '';
});
