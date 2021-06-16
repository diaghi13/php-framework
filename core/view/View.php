<?php


namespace application\core\view;


use application\core\Application;

class View {
    public function renderView(string $view, $params = []): string {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->viewContent($view, $params);
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    public function renderContent(string $viewContent): string {
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    private function layoutContent(): bool|string {
        ob_start();
        $layout = Application::$app->controller->layout ?? 'main';
        include_once Application::$ROOT_PATH . "/views/layout/$layout.php";
        return ob_get_clean();
    }

    private function viewContent(string $view, array $params): bool|string {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_PATH . "/views/$view.php";
        return ob_get_clean();
    }
}