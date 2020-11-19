<?php

namespace App\Packages\Core\Engine;

use App\Packages\Core\Repositories\UserRepository;

class Auth
{
    public const IS_LOGGED = 'is_logged';
    public const USER = 'user';

    private Session $session;
    private UserRepository $userRepository;

    public function __construct(Session $session, UserRepository $userRepository)
    {
        $this->session = $session;
        $this->userRepository = $userRepository;
    }

    /**
     * Пользователь за логинен?
     * @return bool
     */
    public function userIsLogged(): bool
    {
        return (bool) $this->session->get(self::IS_LOGGED);
    }

    /**
     * Установить пользователя
     * @param  array  $user
     */
    public function setUser(array $user): void
    {
        $this->session->set(self::USER, $user);
    }

    /**
     * Получить текущего пользователя
     *
     * @return array
     */
    public function user(): array
    {
        if ($this->userIsLogged()) {
            return (array) $this->session->get(self::USER);
        }

        return [];
    }

    public function login(string $login, string $password)
    {
        $hash = md5($password);
        $user = $this->userRepository->findByLoginAndPassword($login, $hash);

        if ($user) {
            unset($user['password']);
            $this->session->set(self::IS_LOGGED, true);
            $this->session->set(self::USER, $user);
        }

        return $user;
    }

    /**
     * Очистка сессии
     */
    public function logout(): void
    {
        $this->session->set(self::IS_LOGGED, false);
        $this->session->set(self::USER, []);
    }

}
