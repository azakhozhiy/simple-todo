<?php

namespace App\Packages\Core\Engine;

use App\Packages\Core\Database;
use Cake\Database\Exception;
use Closure;
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

    public function __construct(string $basePath, array $config)
    {
        $this->setBasePath($basePath);
        $this->config = $config;

        self::$instance = $this;
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

    public function bind($abstract, $concrete = null, $shared = false): void
    {
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

        throw new Exception('Bind not exists.');
    }
}
