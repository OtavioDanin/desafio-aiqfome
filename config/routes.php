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
use Modules\Favorites\UI\FavoriteController;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'Modules\Clients\UI\Controllers\IndexController@index');

Router::addGroup('/api', function () {
    Router::addGroup('/clients', function () {
        Router::get('', [ClientController::class, 'index']);
        Router::post('', [ClientController::class, 'store']);
        Router::get('/{id}', [ClientController::class, 'show']);
        Router::put('/{id}', [ClientController::class, 'update']);
        Router::delete('/{id}', [ClientController::class, 'destroy']);
    });
    
    Router::addGroup('/favorites', function () {
    //     Router::get('', [App\Controller\FavoritoController::class, 'index']);
        Router::post('', [FavoriteController::class, 'store']);
    //     Router::delete('/{id}', [App\Controller\FavoritoController::class, 'destroy']);
    });
});

Router::get('/favicon.ico', function () {
    return '';
});
