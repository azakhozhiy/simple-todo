<?php

declare(strict_types=1);

use App\Packages\Core\Engine\Application;
use App\Packages\Core\Engine\Auth;
use App\Packages\Core\Engine\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

if (!function_exists('auth')) {
    function auth()
    {
        /** @var Auth $auth */
        $auth = app()->make(Auth::class);

        return $auth;
    }
}

if (!function_exists('jsonResponse')) {
    function jsonResponse(array $data, int $status_code = 200): JsonResponse
    {
        return (new JsonResponse($data, $status_code))
            ->send();
    }
}

if (!function_exists('response')) {
    function response($data, int $status_code = 200): Response
    {
        return (new Response($data, $status_code))
            ->prepare(app()->make(Request::class))
            ->send();
    }
}

if (!function_exists('view')) {
    /**
     * @param  string  $page
     * @param  array  $args
     * @param  bool  $is_part
     * @return false|string
     */
    function view(string $page, array $args = [], bool $is_part = true)
    {
        $file_path = $page.'.php';
        $template_path = views_path($file_path);
        $header = views_path('parts/header.php');
        $footer = views_path('parts/footer.php');

        if (!file_exists($template_path)) {
            throw new RuntimeException('Template not found.');
        }

        if (!file_exists($header)) {
            throw new RuntimeException('Header not found.');
        }

        if (!file_exists($footer)) {
            throw new RuntimeException('Footer not found.');
        }

        if (count($args)) {
            extract($args);
        }

        ob_start();

        if ($is_part) {
            include $header;
        }

        include $template_path;

        if ($is_part) {
            include $footer;
        }

        print ob_get_clean();
    }
}

if (!function_exists('session')) {
    function session(): Session
    {
        return app()->make(Session::class);
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

