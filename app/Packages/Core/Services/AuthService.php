<?php

declare(strict_types=1);

namespace App\Packages\Core\Services;

use App\Packages\Core\Engine\Session;
use App\Packages\Core\Models\User;

class AuthService
{
    protected Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function login(string $email, string $password): void
    {
        $password_hash = md5($password);

        $user = User::query("SELECT * FROM users WHERE email=:email AND password=:password");

        $this->session->set('user', $user);
    }

    public function logout(): void
    {
        $this->session->forget('user');
    }
}
