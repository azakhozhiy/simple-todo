<?php

use App\Controllers\AuthController;
use App\Controllers\IndexController;
use App\Packages\Core\Engine\Router;
use App\Packages\Core\Services\AuthService;
use App\Packages\Files\Managers\FileManager;
use App\Packages\Tasks\Controllers\TaskController;
use App\Packages\Tasks\Managers\TaskManager;
use App\Packages\Tasks\Repositories\TaskRepository;

return [
    'main' => [
        'controller' => IndexController::class,
        'actions' => [
            'index' => [
                'method_type' => Router::GET,
                'method' => 'index',
            ],
        ],
    ],
    'auth' => [
        'controller' => AuthController::class,
        'actions' => [
            'logout' => [
                'method_type' => Router::GET,
                'method' => 'logout',
            ],
            'login' => [
                'method_type' => Router::POST,
                'method' => 'login',
            ],
        ],
    ],
    'tasks' => [
        'controller' => TaskController::class,
        'actions' => [
            'touch' => [
                'method' => 'createOrUpdate',
                'method_type' => Router::POST,
            ],
            'toggle' => [
                'method' => 'toggleCompleted',
                'method_type' => Router::POST,
            ],
        ],
    ],
];
