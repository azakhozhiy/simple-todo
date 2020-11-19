<?php

declare(strict_types=1);

namespace App\Packages\Tasks\Controllers;

use App\Packages\Core\Engine\Auth;
use App\Packages\Files\Managers\FileManager;
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

    public function getContent(Request $request): JsonResponse
    {

    }

    public function createOrUpdate(Request $request, FileManager $fileManager, Auth $auth): JsonResponse
    {
        $data = $request->request;
        $task_id = $data->get('task_id', null);
        $title = $data->get('title', null);
        $content = $data->get('content', null);
        $picture = $request->files->get('picture');

        // Edit action allowed only users
        if ($task_id && !$auth->userIsLogged()) {
            return jsonResponse(['error' => 'You cannot edit.'], 400);
        }

        $task = $task_id ? $this->taskRepository->findOneById($task_id) : null;

        // If task exist in request, but not found in database
        if ($task_id && !$task) {
            return jsonResponse(['error' => 'Task not found.'], 400);
        }

        $user = $auth->user();

        $creator_id = $user['id'] ?? null;

        // Upload picture
        $fileManager->upload($picture);

        return jsonResponse([]);
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
