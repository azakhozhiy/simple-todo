<?php

declare(strict_types=1);

namespace App\Packages\Core\Services;

use App\Packages\Core\Models\User;

class AuthService
{
    public function login(string $email, string $password): void
    {
        $password_hash = md5($password);

        $user = User::query("SELECT * FROM users WHERE email = '$email' AND password = '$password_hash'");

        // set to session or cookies
    }

    public function logout(): void
    {
        // clear session or cookie
    }
}
