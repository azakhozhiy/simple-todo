<?php

use App\Controllers\AuthController;
use App\Controllers\IndexController;
use App\Packages\Core\Engine\Router;
use App\Packages\Files\Controllers\FileController;
use App\Packages\Tasks\Controllers\TaskController;

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
    'files' => [
        'controller' => FileController::class,
        'actions' => [
            'get' => [
                'method_type' => Router::GET,
                'method' => 'getByOriginalName',
            ],
            'placeholder' => [
                'method_type' => Router::GET,
                'method' => 'getPlaceholder',
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
            'getContent' => [
                'method' => 'getContent',
                'method_type' => Router::GET,
            ],
        ],
    ],
];
