<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Small path-based router. No external routing library, per project constraints.
 *
 * Route patterns use `{param}` placeholders, e.g. "/video/{id}".
 */
final class Router
{
    /** @var array<int, array{method: string, pattern: string, handler: callable|array{0: class-string, 1: string}}> */
    private array $routes = [];

    public function get(string $pattern, callable|array $handler): void
    {
        $this->routes[] = ['method' => 'GET', 'pattern' => $pattern, 'handler' => $handler];
    }

    /**
     * @param string $basePath The app's base directory (e.g. "/AI-UNIT/public"
     *                         when installed under a subdirectory), stripped
     *                         from the request path before route matching.
     */
    public function dispatch(string $method, string $uri, string $basePath = ''): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        $basePath = rtrim($basePath, '/');
        if ($basePath !== '' && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
        }

        $path = $path === '' ? '/' : rtrim($path, '/');
        if ($path === '') {
            $path = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = $this->match($route['pattern'], $path);
            if ($params === null) {
                continue;
            }

            $this->call($route['handler'], $params);

            return;
        }

        http_response_code(404);
        View::render('404', [], 'app');
    }

    /**
     * @return array<string, string>|null
     */
    private function match(string $pattern, string $path): ?array
    {
        $pattern = $pattern === '' ? '/' : rtrim($pattern, '/');
        if ($pattern === '') {
            $pattern = '/';
        }

        $paramNames = [];
        $regex = preg_replace_callback('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', function (array $m) use (&$paramNames) {
            $paramNames[] = $m[1];

            return '([^/]+)';
        }, $pattern);

        $regex = '#^' . $regex . '$#';

        if (!preg_match($regex, $path, $matches)) {
            return null;
        }

        array_shift($matches);

        return array_combine($paramNames, $matches) ?: [];
    }

    /**
     * @param array<string, string> $params
     */
    private function call(callable|array $handler, array $params): void
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $controller = new $class();
            call_user_func_array([$controller, $method], $params);

            return;
        }

        call_user_func_array($handler, $params);
    }
}
