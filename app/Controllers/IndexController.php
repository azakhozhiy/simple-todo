<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Packages\Core\Repositories\UserRepository;
use App\Packages\Files\Repositories\FileRepository;
use App\Packages\Tasks\Repositories\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index(Request $request, UserRepository $userRepository, FileRepository $fileRepository): Response
    {
        //$page = $request->get('page', 1);
        $order_by = ['id', 'desc'];

        $tasks = $this->taskRepository->getAll([], $order_by);

        foreach ($tasks as $key => $task) {
            if (isset($task['creator_id']) && $task['creator_id']) {
                $tasks[$key]['creator'] = $userRepository->getOneById($task['creator_id']);
            }

            $tasks[$key]['picture_url'] = url('?module=files&action=placeholder');
            if (isset($task['picture_id']) && $task['picture_id']) {
                $file = $fileRepository->getOneById($task['picture_id']);
                $tasks[$key]['picture_url'] = url('?module=files&action=get&file_name='.$file['original_name']);
            }
        }

        return new Response(view('main', ['tasks' => $tasks]));
    }
}
