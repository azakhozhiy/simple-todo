<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Packages\Core\Models\User;
use App\Packages\Tasks\Repositories\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class IndexController
{
    private TaskRepository $taskRepository;
    private SessionInterface $session;

    public function __construct(TaskRepository $taskRepository, SessionInterface $session)
    {
        $this->taskRepository = $taskRepository;
        $this->session = $session;
    }

    public function index(Request $request): Response
    {
        $userQuery = User::query()->query("SELECT * from users");

        return new Response(view('layout.php', ['userQuery' => $userQuery]));
    }

    public function taskEdit(Request $request): Response
    {
    }

    public function finishTask(Request $request): Response
    {
    }
}
