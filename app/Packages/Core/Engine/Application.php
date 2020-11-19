<?php

declare(strict_types=1);

namespace App\Packages\Core\Engine;

use Closure;
use Exception;
use RuntimeException;
use TypeError;

class Application
{
    protected Database $database;
    protected ?string $basePath = null;
    protected bool $dbIsInitialized = false;
    protected static ?Application $instance = null;
    protected array $bindings = [];
    protected array $instances = [];
    protected array $config = [];

    /**
     * Application constructor.
     * @param  string  $basePath
     * @param  array  $config
     * @throws Exception
     */
    public function __construct(string $basePath, array $config)
    {
        $this->setBasePath($basePath);
        $this->config = $config;

        $this->initDatabase($config);

        self::$instance = $this;
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }

    /**
     * @param  array  $config
     * @throws RuntimeException
     */
    private function initDatabase(array $config): void
    {
        if (!isset($config['env'])) {
            throw new RuntimeException('App config not found.');
        }

        $app_config = $config['app'];

        $db_host = $app_config['db_host'] ?? null;
        $db_user = $app_config['db_username'] ?? null;
        $db_password = $app_config['db_password'] ?? null;
        $db_port = $app_config['db_port'] ?? null;
        $db_name = $app_config['db_database'] ?? null;

        if (!$db_name || !$db_user || !$db_password || !$db_port || !$db_host) {
            throw new RuntimeException('Invalid credentials for connection to database.');
        }

        $this->database = new Database($db_host, $db_port, $db_name, $db_user, $db_password);
    }

    public static function getInstance(): Application
    {
        if (self::$instance) {
            return self::$instance;
        }

        return new static;
    }

    public function setBasePath(string $basePath): void
    {
        $this->basePath = rtrim($basePath, '\/');
    }

    public function publicPath(?string $path = null): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'public'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    public function resourcesPath(?string $path = null): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'resources'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    public function storagePath(?string $path = null): string
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'storage'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    public function singleton(string $abstract, $concrete = null): void
    {
        $this->bind($abstract, $concrete);
    }

    public function bind($abstract, $concrete = null): void
    {
        $this->dropStaleInstances($abstract);

        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        if (!$concrete instanceof Closure) {
            if (!is_string($concrete)) {
                throw new TypeError(self::class.'::bind(): Argument #2 ($concrete) must be of type Closure|string|null');
            }

            $concrete = $this->getClosure($abstract, $concrete);
        }

        $this->bindings[$abstract] = $concrete;

        if (isset($this->instances[$abstract])) {
            $this->instances[$abstract] = $concrete($this);
        }
    }

    protected function dropStaleInstances($abstract): void
    {
        unset($this->bindings[$abstract]);
    }

    public function initializeBind($concrete)
    {
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        return new $concrete($this);
    }

    /**
     * @param $abstract
     * @param $concrete
     * @return Closure
     */
    protected function getClosure($abstract, $concrete): callable
    {
        return static function (Application $container) use ($abstract, $concrete) {
            return $container->initializeBind($concrete);
        };
    }

    public function make($abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if (isset($this->bindings[$abstract])) {
            $initialized = $this->bindings[$abstract]($this);
            $this->instances[$abstract] = $initialized;

            return $initialized;
        }

        throw new RuntimeException('Bind not exists. Abstract: ' . $abstract);
    }
}
