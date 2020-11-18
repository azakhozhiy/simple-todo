<?php

namespace App\Packages\Core\Engine;

use Closure;
use Exception;
use InvalidArgumentException;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use RuntimeException;

class BoundMethod
{
    public static function call(
        Application $container,
        $callback,
        array $parameters = [],
        ?string $defaultMethod = null
    ) {
        if (is_string($callback) && !$defaultMethod && method_exists($callback, '__invoke')) {
            $defaultMethod = '__invoke';
        }

        if ($defaultMethod || static::isCallableWithAtSign($callback)) {
            return static::callClass($container, $callback, $parameters, $defaultMethod);
        }

        return static::callBoundMethod($container, $callback, function () use ($container, $callback, $parameters) {
            return call_user_func_array(
                $callback, static::getMethodDependencies($container, $callback, $parameters)
            );
        });
    }


    /**
     * @param $container
     * @param $target
     * @param  array  $parameters
     * @param  null  $defaultMethod
     * @return mixed
     */
    protected static function callClass($container, $target, array $parameters = [], $defaultMethod = null)
    {
        $segments = explode('@', $target);

        // We will assume an @ sign is used to delimit the class name from the method
        // name. We will split on this @ sign and then build a callable array that
        // we can pass right back into the "call" method for dependency binding.
        $method = count($segments) === 2
            ? $segments[1] : $defaultMethod;

        if (is_null($method)) {
            throw new InvalidArgumentException('Method not provided.');
        }

        return static::call(
            $container, [$container->make($segments[0]), $method], $parameters
        );
    }

    protected static function callBoundMethod($container, $callback, $default)
    {
        if (!is_array($callback)) {
            return Util::unwrapIfClosure($default);
        }

        // Here we need to turn the array callable into a Class@method string we can use to
        // examine the container and see if there are any method bindings for this given
        // method. If there are, we can call this method binding callback immediately.
        $method = static::normalizeMethod($callback);

        if ($container->hasMethodBinding($method)) {
            return $container->callMethodBinding($method, $callback[0]);
        }

        return Util::unwrapIfClosure($default);
    }

    protected static function normalizeMethod(callable $callback): string
    {
        $class = is_string($callback[0]) ? $callback[0] : get_class($callback[0]);

        return "{$class}@{$callback[1]}";
    }

    /**
     * @param $container
     * @param $callback
     * @param  array  $parameters
     * @return array
     * @throws ReflectionException
     * @throws Exception
     */
    protected static function getMethodDependencies($container, $callback, array $parameters = []): array
    {
        $dependencies = [];

        $reflectorParams = static::getCallReflector($callback)->getParameters();

        foreach ($reflectorParams as $parameter) {
            static::addDependencyForCallParameter($container, $parameter, $parameters, $dependencies);
        }

        return array_merge($dependencies, $parameters);
    }

    /**
     * Get the proper reflection instance for the given callback.
     *
     * @param  callable|string  $callback
     * @return \ReflectionFunctionAbstract
     *
     * @throws ReflectionException
     */
    protected static function getCallReflector($callback)
    {
        if (is_string($callback) && strpos($callback, '::') !== false) {
            $callback = explode('::', $callback);
        } elseif (is_object($callback) && !$callback instanceof Closure) {
            $callback = [$callback, '__invoke'];
        }

        return is_array($callback)
            ? new ReflectionMethod($callback[0], $callback[1])
            : new ReflectionFunction($callback);
    }

    /**
     * @param $container
     * @param $parameter
     * @param  array  $parameters
     * @param $dependencies
     * @throws Exception
     */
    protected static function addDependencyForCallParameter(
        $container,
        $parameter,
        array &$parameters,
        &$dependencies
    ): void {
        if (array_key_exists($paramName = $parameter->getName(), $parameters)) {
            $dependencies[] = $parameters[$paramName];

            unset($parameters[$paramName]);
        } elseif (!is_null($className = Util::getParameterClassName($parameter))) {
            if (array_key_exists($className, $parameters)) {
                $dependencies[] = $parameters[$className];

                unset($parameters[$className]);
            } else {
                $dependencies[] = $container->make($className);
            }
        } elseif ($parameter->isDefaultValueAvailable()) {
            $dependencies[] = $parameter->getDefaultValue();
        } elseif (!array_key_exists($paramName, $parameters) && !$parameter->isOptional()) {
            $message = "Unable to resolve dependency [{$parameter}] in class {$parameter->getDeclaringClass()->getName()}";

            throw new RuntimeException($message);
        }
    }

    /**
     * Determine if the given string is in Class@method syntax.
     *
     * @param  mixed  $callback
     * @return bool
     */
    protected static function isCallableWithAtSign($callback): bool
    {
        return is_string($callback) && strpos($callback, '@') !== false;
    }
}
