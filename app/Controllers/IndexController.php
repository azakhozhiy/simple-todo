<?php

namespace App\Controllers;

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

    public function index(Request $request): Response
    {
        return new Response(view('layout.php'));
    }

    public function taskEdit(Request $request): Response
    {
    }

    public function finishTask(Request $request): Response
    {
    }
}
