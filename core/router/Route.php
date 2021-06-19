<?php


namespace application\core\router;


class Route {
    public array $routes = [];

    public function get(string $uri, $action = null) {
        $this->routes['get'][] = [
            'uri' => $uri,
            'action' => $action
        ];
    }

    public function post(string $uri, $action = null) {
        $this->routes['post'][] = [
            'uri' => $uri,
            'action' => $action
        ];
    }

    public function patch(string $uri, $action = null) {
        $this->routes['patch'][] = [
            'uri' => $uri,
            'action' => $action
        ];
    }

    public function put(string $uri, $action = null) {
        $this->routes['put'][] = [
            'uri' => $uri,
            'action' => $action
        ];
    }

    public function delete(string $uri, $action = null) {
        $this->routes['delete'] = [
            'uri' => $uri,
            'action' => $action
        ];
    }

    public function middleware(string $middleware) {

    }
}