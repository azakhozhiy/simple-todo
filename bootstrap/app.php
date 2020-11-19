<?php

declare(strict_types=1);

use App\Packages\Core\Engine\Application;
use App\Packages\Core\Engine\Auth;
use App\Packages\Core\Engine\Router;
use App\Packages\Core\Engine\Session;
use App\Packages\Core\Repositories\UserRepository;
use App\Packages\Files\Managers\FileManager;
use App\Packages\Files\Repositories\FileRepository;
use App\Packages\Tasks\Managers\TaskManager;
use App\Packages\Tasks\Repositories\TaskRepository;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\Request;

Session::start();

$config = require __DIR__.'/../config.php';
$routes = require __DIR__.'/routes.php';

$app = new Application(dirname(__DIR__).'/', $config);

$app->singleton(Session::class);
$app->singleton(UserRepository::class);
$app->singleton(ImageManager::class, fn() => new ImageManager(['driver' => 'gd']));

$app->singleton(Auth::class, function (Application $app) {
    return new Auth($app->make(Session::class), $app->make(UserRepository::class));
});
$app->singleton(Request::class, fn(Application $app) => Request::createFromGlobals());
$app->singleton(Router::class, fn(Application $app) => new Router($app, $routes));

$app->singleton(FileRepository::class);

$app->singleton(FileManager::class, function (Application $app) {
    return new FileManager($app->make(ImageManager::class));
});

$app->singleton(TaskManager::class);
$app->singleton(TaskRepository::class);

return $app;

