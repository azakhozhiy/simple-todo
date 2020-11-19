<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Packages\Core\Engine\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    protected Auth $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function login(Request $request): JsonResponse
    {
        if ($this->auth->userIsLogged()) {
            return jsonResponse(['message' => 'You are already logged in.']);
        }

        $data = $request->getContent();
        $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        $login = $data['login'] ?? null;
        $password = $data['password'] ?? null;

        if (!$login || !$password) {
            return jsonResponse(['error' => 'Login or password cannot be a null.'], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->auth->login($login, $password);

        if (!$user) {
            return jsonResponse(['error' => 'Invalid credentials.'], Response::HTTP_UNAUTHORIZED);
        }

        return jsonResponse($user);
    }

    public function logout(): RedirectResponse
    {
        $this->auth->logout();

        return (new RedirectResponse('/'))->send();
    }
}
