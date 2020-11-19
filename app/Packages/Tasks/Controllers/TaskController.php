<?php

declare(strict_types=1);

namespace App\Packages\Tasks\Controllers;

use App\Packages\Core\Engine\Auth;
use App\Packages\Files\Managers\FileManager;
use App\Packages\Files\Support\Image;
use App\Packages\Tasks\Managers\TaskManager;
use App\Packages\Tasks\Models\Task;
use App\Packages\Tasks\Repositories\TaskRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

    public function createOrUpdate(Request $request, FileManager $fileManager, Auth $auth): JsonResponse
    {
        $data = $request->request;

        $task_id = (int) $data->get('id', null);
        $title = $data->get('title', null);
        $content = $data->get('content', null);
        /** @var UploadedFile $picture */
        $picture = $request->files->get('picture');

        // Edit action allowed only users
        if ($task_id && !$auth->userIsLogged()) {
            return jsonResponse(['error' => 'You cannot edit.'], 400);
        }

        $task = $task_id ? $this->taskRepository->getOneById($task_id) : null;

        // If task exist in request, but not found in database
        if ($task_id && !$task) {
            return jsonResponse(['error' => 'Task not found.'], 400);
        }

        $user = $auth->user();
        $name = str2translit($title);

        $creator_id = $user['id'] ?? null;

        $this->taskManager->createOrUpdate($task_id, $creator_id, $title, $content);

        return jsonResponse([]);
    }

    public function toggleCompleted(Request $request, Auth $auth): JsonResponse
    {
        $task_id = (int) $request->get('task_id');

        if (!$auth->userIsLogged()) {
            return jsonResponse(['error' => 'Bad request.'], 400);
        }

        if (!$task_id) {
            return jsonResponse(['error' => 'Bad request.'], 400);
        }

        $task = $this->taskRepository->getOneById($task_id);

        if (!$task) {
            return jsonResponse(['error' => 'Task not found.'], 400);
        }

        $this->taskManager->changeComplete($task_id, !$task['is_complete']);

        return jsonResponse(['is_completed' => !$task['is_complete']]);
    }
}
