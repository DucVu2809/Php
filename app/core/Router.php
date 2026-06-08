<?php

/**
 * Bộ định tuyến (Router) đơn giản hỗ trợ tham số động.
 *
 * Cú pháp khai báo route: '/san-pham/{slug}' với phương thức GET/POST.
 * Tham số trong {} được trích ra và truyền lần lượt cho action của controller.
 */

declare(strict_types=1);

namespace App\Core;

class Router
{
    /** @var array<int,array{method:string,pattern:string,handler:array}> */
    private array $routes = [];

    /**
     * Đăng ký route GET.
     *
     * @param array{0:class-string,1:string} $handler [Controller::class, 'action']
     */
    public function get(string $pattern, array $handler): void
    {
        $this->add('GET', $pattern, $handler);
    }

    /**
     * Đăng ký route POST.
     *
     * @param array{0:class-string,1:string} $handler
     */
    public function post(string $pattern, array $handler): void
    {
        $this->add('POST', $pattern, $handler);
    }

    /**
     * @param array{0:class-string,1:string} $handler
     */
    private function add(string $method, string $pattern, array $handler): void
    {
        $this->routes[] = [
            'method'  => $method,
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    /**
     * Khớp URI hiện tại với các route đã đăng ký rồi gọi controller tương ứng.
     */
    public function dispatch(string $method, string $uri): void
    {
        $path = $this->normalize($uri);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = $this->matchPattern($route['pattern'], $path);
            if ($params === null) {
                continue;
            }

            [$class, $action] = $route['handler'];
            $controller = new $class();
            $controller->{$action}(...array_values($params));
            return;
        }

        $this->notFound();
    }

    /**
     * Bỏ BASE_URL và query string, chuẩn hoá thành path bắt đầu bằng '/'.
     */
    private function normalize(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        if (BASE_URL !== '' && str_starts_with($path, BASE_URL)) {
            $path = substr($path, strlen(BASE_URL));
        }

        $path = '/' . trim($path, '/');
        return $path === '/' ? '/' : rtrim($path, '/');
    }

    /**
     * So khớp một pattern có {tham_số} với path thực tế.
     *
     * @return array<string,string>|null  Mảng tham số nếu khớp, null nếu không.
     */
    private function matchPattern(string $pattern, string $path): ?array
    {
        $pattern = $pattern === '/' ? '/' : rtrim($pattern, '/');
        $regex   = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $pattern);
        $regex   = '#^' . $regex . '$#u';

        if (!preg_match($regex, $path, $matches)) {
            return null;
        }

        return array_filter(
            $matches,
            static fn ($key): bool => !is_int($key),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Trả về trang 404.
     */
    private function notFound(): void
    {
        http_response_code(404);
        View::render('errors/404', [], 'main');
    }
}
