<?php

namespace App\Packages\Core\Engine;

use ReflectionException;
use ReflectionMethod;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Router
{
    public const GET = 'GET';
    public const POST = 'POST';

    protected array $routes;
    protected Request $request;
    protected string $defaultModule = 'main';
    protected string $defaultAction = 'index';
    protected Application $app;

    public function __construct(Application $app, array $routes)
    {
        $this->request = $app->make(Request::class);
        $this->routes = $routes;
        $this->app = $app;
    }

    private function validateModule(array $module): void
    {
        if (!isset($module['controller'], $module['actions'])) {
            throw new RuntimeException('Module is invalid.');
        }
    }

    private function validateAction(array $action): void
    {
        if (!isset($action['method'])) {
            throw new RuntimeException('Action must have method name.');
        }

        if (!isset($action['method_type'])) {
            throw new RuntimeException('Action must have method type.');
        }
    }

    private function actionIsExistInModule(array $module, string $action_name): bool
    {
        return isset($module['actions'][$action_name]);
    }

    /**
     * @param  string  $class
     * @param $method
     * @return array
     * @throws ReflectionException
     */
    public function getMethodDependencies(string $class, $method): array
    {
        $refMethod = new ReflectionMethod($class, $method);

        $parameters = $refMethod->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            if ($parameter->getType()) {
                $argName = $parameter->getType()->getName();
                $dependencies[] = $this->app->make($argName);
            }
        }

        return $dependencies;
    }

    /**
     * @param $controller
     * @param  string  $method_name
     * @return Response
     * @throws ReflectionException
     */
    public function call($controller, string $method_name): Response
    {
        $controllerInstance = new $controller(...$this->getMethodDependencies($controller, '__construct'));

        return $controllerInstance->{$method_name}(...$this->getMethodDependencies($controller, $method_name));
    }

    /**
     * @return array|Response
     * @throws ReflectionException
     */
    public function dispatch()
    {
        $module_name = $this->request->get('module', $this->defaultModule);
        $action_name = $this->request->get('action', $this->defaultAction);
        $method_type = $this->request->getMethod();

        $controller = null;
        $method = null;

        if (isset($this->routes[$module_name])) {
            //validate module
            $module = $this->routes[$module_name];
            $this->validateModule($module);

            $controller = $module['controller'];

            if ($this->actionIsExistInModule($module, $action_name)) {
                $action = $module['actions'][$action_name];
                $this->validateAction($action);

                if ($action['method_type'] !== $method_type) {
                    return [];
                }

                $method = $action['method'];
            }
        }

        if (!class_exists($controller)) {
            throw new RuntimeException('Сontroller must be a class.');
        }

        if (!method_exists($controller, $method)) {
            throw new RuntimeException('Method in controller not exist.');
        }

        return $this->call($controller, $method);
    }
}
