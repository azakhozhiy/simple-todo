<?php

declare(strict_types=1);

namespace App\Packages\Tasks\Controllers;

use App\Packages\Core\Engine\Auth;
use App\Packages\Files\Managers\FileManager;
use App\Packages\Tasks\Managers\TaskManager;
use App\Packages\Tasks\Models\Task;
use App\Packages\Tasks\Repositories\TaskRepository;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TaskController
{
    private TaskRepository $taskRepository;
    private TaskManager $taskManager;

    public function __construct(
        TaskRepository $taskRepository,
        TaskManager $taskManager
    ) {
        $this->taskRepository = $taskRepository;
        $this->taskManager = $taskManager;
    }

    public function getContent(Request $request): JsonResponse
    {
        $task_id = (int) $request->get('task_id');

        if (!$task_id) {
            return jsonResponse(['error' => 'Bad request'], 400);
        }

        $task = $this->taskRepository->getOneById($task_id);

        if (!$task) {
            return jsonResponse(['error' => 'Task not found.']);
        }

        return jsonResponse(['content' => $task['content']]);
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

        if (!$title) {
            return jsonResponse(['error' => 'Title reqired.'], 400);
        }

        $task = $task_id ? $this->taskRepository->getOneById($task_id) : null;

        // If task exist in request, but not found in database
        if ($task_id && !$task) {
            return jsonResponse(['error' => 'Task not found.'], 400);
        }

        $user = $auth->user();
        $name = $this->taskRepository->genUniqName($title);

        $creator_id = $user['id'] ?? null;

        try {
            $this->taskManager->getConnection()->beginTransaction();

            $task_id = $this->taskManager->createOrUpdate($task_id, $creator_id, $title, $name, $content);

            $task = [
                'id' => $task_id,
                'name' => $name,
                'title' => $title,
                'creator_id' => $creator_id,
                'content' => $content,
            ];

            if ($picture) {
                $dir = Task::pictureFolder($task_id);
                $picture_id = $fileManager->create($creator_id, $picture, $dir);
                $this->taskManager->associatePicture($task_id, $picture_id);
            }

            $this->taskManager->getConnection()->commit();
        } catch (Exception $e) {
            $this->taskManager->getConnection()->rollBack();

            return jsonResponse(['message' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }

        return jsonResponse($task);
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
