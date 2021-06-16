<?php


namespace application\core\support\facades;


use application\core\Application;

/**
 * @method static \application\core\router\Route get(string $uri, string|array|callable|null $action = null);
 * @method static \application\core\router\Route post(string $uri, string|array|callable|null $action = null);
 * @method static \application\core\router\Route patch(string $uri, string|array|callable|null $action = null);
 * @method static \application\core\router\Route put(string $uri, string|array|callable|null $action = null);
 * @method static \application\core\router\Route delete(string $uri, string|array|callable|null $action = null);
 */
class Route {
    public static function __callStatic(string $httpMethod, array $args) {
        Application::$app->router->route->$httpMethod($args[0], $args[1]);
    }
}