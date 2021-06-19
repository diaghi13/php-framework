<?php


namespace application\core\http;


use application\core\Application;
use application\core\http\middleware\Middleware;

class Controller {
    public string $layout = 'main';

    protected array $params = [];
    protected array $middlewares = [];

    public string $action = '';

    public function __construct() {
        $this->params = Application::$app->router->matchRoute['params'] ?? [];
    }

    protected function render(string $view, array $params = []): string {
        //return Application::$app->router->renderView($view, $params);
        return Application::$app->view->renderView($view, $params);
    }

    public function registerMiddleware(Middleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}