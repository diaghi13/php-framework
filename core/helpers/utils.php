<?php

use application\core\Application;

function env($key) {
    if (is_string($key)) {
        return $_ENV[$key] ?? null;
    }

    if (is_array($key)) {
        $params = [];
        foreach ($key as $k => $value) {
            $params[][$k] = $value;
        }
        return  $params;
    }
}

function view(string $view, array $params = []): string {
    return Application::$app->view->renderView($view, $params);
}

function input_csrf_token(): string {
    $token = $_SESSION['token'];
    return '<input type="hidden" name="csrf_token" value="'.$token.'"/>';
}

function csrf_token() {
    return $_SESSION['token'];
}