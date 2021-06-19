<?php


namespace application\app\middlewares;


use application\core\Application;
use application\core\exceptions\ForbiddenException;
use application\core\http\middleware\Middleware;
use application\core\http\Request;

class AuthMiddleware extends Middleware {

    public function handle(Request $request): Request {
        if (Application::$app->isGuest()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)){
                throw new ForbiddenException();
            }
        }

        return $request;
    }
}