<?php

declare(strict_types=1);

namespace App\Packages\Tasks\Controllers;

use App\Packages\Tasks\Managers\TaskManager;
use App\Packages\Tasks\Repositories\TaskRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TaskController
{
    private TaskRepository $taskRepository;
    private TaskManager $taskManager;

    public function __construct(TaskRepository $taskRepository, TaskManager $taskManager)
    {
        $this->taskRepository = $taskRepository;
        $this->taskManager = $taskManager;
    }

    public function edit(Request $request): JsonResponse
    {
        $task_id = $request->get('task_id');

        if (!$task_id) {
            return (new JsonResponse(['error' => 'Task not found.']))->setStatusCode(400);
        }

        return (new JsonResponse())->setStatusCode(200);
    }

    public function finish(Request $request): JsonResponse
    {
        $task_id = $request->get('task_id');

        if (!$task_id) {
            return (new JsonResponse(['error' => 'Task not found.']))->setStatusCode(400);
        }

        return (new JsonResponse(['message' => 'Successfully.']))->setStatusCode(200);
    }
}
