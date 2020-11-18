<?php

namespace App\Packages\Tasks\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TaskController
{
    public function __construct()
    {
    }

    public function edit(Request $request)
    {
        return (new JsonResponse())->setStatusCode(200);
    }

    public function finish(Request $request)
    {
        $user = $request->getSession()->get('user');

        if (!$user) {
            return (new JsonResponse(['error' => 'Bad request.']))->setStatusCode(400);
        }

        return (new JsonResponse(['message' => 'Successfully.']))->setStatusCode(200);
    }
}
