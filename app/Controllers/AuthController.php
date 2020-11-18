<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Packages\Core\Services\AuthService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    private AuthService $authService;

    public function construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request): Response
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $this->authService->login($email, $password);
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();

        return (new RedirectResponse('/'));
    }
}
