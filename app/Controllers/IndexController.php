<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Packages\Core\Engine\Session;
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

    public function index(Request $request, Session $session): Response
    {
        $page = $request->get('page', 1);

        $taskQuery = $this->taskRepository->query();

        return new Response(view('index', ['taskQuery' => $taskQuery]));
    }
}
