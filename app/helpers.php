<?php

declare(strict_types=1);

use App\Packages\Core\Engine\Application;
use Symfony\Component\HttpFoundation\Request;

if (!function_exists('app')) {
    /**
     * @param  string|null  $abstract
     * @return Application|mixed
     */
    function app(string $abstract = null)
    {
        if (is_null($abstract)) {
            return Application::getInstance();
        }

        return Application::getInstance()->make($abstract);
    }
}

if (!function_exists('request')) {
    function request(): Request
    {
        return Request::createFromGlobals();
    }
}

if (!function_exists('view')) {
    /**
     * @param  string  $file
     * @param  array  $args
     * @return false|string
     * @throws RuntimeException
     */
    function view(string $file, array $args = [])
    {
        $file_path = $file.'.php';
        $template_path = views_path($file_path);

        if (!file_exists($template_path)) {
            throw new RuntimeException('Template not found.');
        }

        if (count($args)) {
            extract($args);
        }

        ob_start();
        include $template_path;

        print ob_get_clean();
    }
}

if (!function_exists('include_view')) {
    /**
     * @param  string  $file
     * @param  array  $args
     * @return false|string
     * @throws RuntimeException
     */
    function include_view(string $file, array $args = [])
    {
        $template_path = views_path($file);

        if (!file_exists($template_path)) {
            throw new RuntimeException('Template not found.');
        }

        if (count($args)) {
            extract($args);
        }

        ob_start();
        include $template_path;

        print ob_get_clean();
    }
}

if (!function_exists('resources_path')) {
    function resources_path(?string $path = null): string
    {
        return app()->resourcesPath($path);
    }
}

if (!function_exists('views_path')) {
    function views_path(?string $path = null): string
    {
        return app()->resourcesPath('views/'.$path);
    }
}

if (!function_exists('storage_path')) {
    function storage_path(?string $path = null): string
    {
        return app()->storagePath($path);
    }
}

if (!function_exists('public_path')) {
    function public_path(?string $path = null): string
    {
        return app()->publicPath();
    }
}

if (!function_exists('starts_with')) {
    function starts_with($haystack, $needle)
    {
        return strpos($haystack, $needle) === 0;
    }
}

if (!function_exists('mix')) {
    /**
     * @param  string  $path
     * @return string
     * @throws JsonException
     */
    function mix(string $path)
    {
        $public_path = app()->publicPath();

        $manifest_file = $public_path.'/mix-manifest.json';

        if (!file_exists($manifest_file)) {
            throw new RuntimeException('The Mix manifest does not exist.');
        }

        $manifest = json_decode(file_get_contents($manifest_file), true, 512, JSON_THROW_ON_ERROR);

        return $manifest[$path] ?? $path;
    }
}

