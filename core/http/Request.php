<?php


namespace application\core\http;


use application\core\exceptions\RequestException;
use JetBrains\PhpStorm\Pure;

class Request {
    public array $acceptMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD'];

    #[Pure] public function method(): string {
        $method = $_SERVER['REQUEST_METHOD'];
        return strtolower($method);
    }

    #[Pure] public function getUrl() {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $path;
    }

    #[Pure] public function isGet(): bool {
        return $this->method() === 'get';
    }

    #[Pure] public function isPost(): bool {
        return $this->method() === 'post';
    }

    #[Pure] public function isPatch(): bool {
        return $this->method() === "put";
    }

    #[Pure] public function isPut(): bool {
        return $this->method() === "put";
    }

    #[Pure] public function isDelete(): bool {
        return $this->method() === "delete";
    }

    public function getBody(): array {
        if (!$this->isApplicationJson()) {
            return $this->getFormData();
        }
        return $this->getJsonData();
    }

    #[Pure] private function getFormData(): array {
        $data = [];
        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->isPost()) {
            if ($this->validateCsrfToken()) {
                foreach ($_POST as $key => $value) {
                    $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            } else {
                $data['error'] = 'Invalid csrf token';
            }
        }
        return $data;
    }

    private function validateCsrfToken() {
        if (!empty($_POST['csrf_token'])) {
            if (hash_equals($_SESSION['token'], $_POST['csrf_token'])) {
                unset($_POST['csrf_token']);
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    private function getJsonData(): array {
        $data = [];
        switch ($this->method()) {
            case 'get':
                break;
            case 'post':
            case 'patch':
            case 'put':
            case 'delete':
                $request = json_decode(file_get_contents("php://input"));
                foreach ($request as $key => $value) {
                    $data[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
                }
                break;
        }
        return $data;
    }

    public function isApplicationJson(): bool {
        return $_SERVER['CONTENT_TYPE'] === 'application/json' ?? false;
    }
}