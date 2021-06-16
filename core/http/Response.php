<?php


namespace application\core\http;


class Response {
    public function setStatusCode(int $code) {
        http_response_code($code);
    }

    public function redirect(string $url) {
        header("Location: $url");
    }

    public function setHeaders(array $headers) {
        foreach ($headers as $key => $value) {
            header("$key: $value");
        }
    }
}