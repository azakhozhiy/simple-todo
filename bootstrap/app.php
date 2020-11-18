<?php

use App\Packages\Core\Engine\Application;
use App\Packages\Core\Engine\Kernel;
use App\Packages\Core\Repositories\UserRepository;
use App\Packages\Tasks\Managers\TaskManager;
use App\Packages\Tasks\Repositories\TaskRepository;

$config = require __DIR__.'/../config.php';

$app = new Application(dirname(__DIR__).'/', $config);

$app->singleton(Kernel::class);

// Application
$app->singleton(TaskManager::class);
$app->singleton(UserRepository::class);
$app->singleton(TaskRepository::class);

return $app;

