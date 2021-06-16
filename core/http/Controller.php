<?php


namespace application\core\http;


use application\core\Application;

class Controller {
    public string $layout = 'main';

    protected array $params = [];

    public function __construct() {
        $this->params = Application::$app->router->matchRoute['params'] ?? [];
    }

    protected function render(string $view, array $params = []): string {
        //return Application::$app->router->renderView($view, $params);
        return Application::$app->view->renderView($view, $params);
    }
}