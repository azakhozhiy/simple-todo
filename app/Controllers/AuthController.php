<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Packages\Core\Services\AuthService;
use Symfony\Component\HttpFoundation\Request;

class AuthController
{
    private AuthService $authService;

    public function construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request): void
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $this->authService->login($email, $password);
    }

    public function logout(): void
    {
        $this->authService->logout();
    }
}
