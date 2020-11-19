<?php

namespace App\Packages\Core\Engine;

class Session
{
    protected array $data = [];

    public static function start(): void
    {
        session_start();
    }

    public function set(string $key, $value): self
    {
        $_SESSION[$key] = $value;

        return $this;
    }

    public function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function getData(): array
    {
        return $_SESSION;
    }

    public function flush(): void
    {
        $_SESSION = [];
    }
}
