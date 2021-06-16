<?php


namespace application\core\router;


use application\core\Application;
use application\core\exceptions\RouteNotFoundException;
use application\core\http\Request;
use application\core\http\Response;
use JetBrains\PhpStorm\Pure;
use Matrix\Exception;

class Router {
    protected const PARAMETERS_REGEX_FORMAT = '%s([\w]+)(\%s?)%s';
    protected const PARAMETERS_DEFAULT_REGEX = '[\w]+';

    protected string $paramModifiers = '{}';
    protected string $paramOptionalSymbol = '?';
    protected string $urlRegex = '/^%s\/?$/u';

    protected string $namespace;
    protected array $where = [];

    public Route $route;
    public Request $request;
    public Response $response;
    public array $matchRoute = [];

    #[Pure] public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
        $this->route = new Route();
    }

    public function resolve() {
        $this->createRegex();

        $method = $this->request->method();
        $requestUri = $this->request->getUrl();

        $this->matchRequest($requestUri, $method);

        $action = $this->matchRoute['action'] ?? false;
        $callback = $action;

        if (!$action) {
            $this->response->setStatusCode(404);
            throw new RouteNotFoundException();
        }
        /*print_r($this->matchRoute);
        die();*/

        if (is_string($action)) {
            return Application::$app->view->renderView($action);
            //return $this->renderView($action);
        }

        if (is_array($action)) {
            $controller = new $action[0];
            $controller->action = $action[1];

            Application::$app->controller = $controller;
            if (count($action) === 1) {
                $callback = [$controller];
            } else {
                $callback = [$controller, $controller->action];
            }
        }

        /*$parameters = $this->matchRoute['params'] ?? null;
        if ($parameters) {
            return call_user_func($callback, ...$parameters);
        }*/
        return call_user_func($callback, $this->request);
    }

    private function matchRequest($requestUri, $method) {
        $withParams = false;

        try {
            for ($i = 0; $i < count($this->route->routes[$method]); $i++) {

                if (isset($this->route->routes[$method][$i]['uriRegex'])) {

                    $routeRegex = $this->route->routes[$method][$i]['uriRegex'];
                    //print_r('/' . $routeRegex . '$/'.PHP_EOL);
                    if (preg_match_all('/' . $routeRegex . '$/', $requestUri)) {
                        $routeIndex = $i;
                        $withParams = true;
                        $this->matchRoute = $this->route->routes[$method][$i];
                        break;
                    }

                } else {
                    $uri = '^' . $this->route->routes[$method][$i]['uri'];
                    $uri = $uri . '$';

                    preg_match('#' . $uri . '#', $requestUri, $matches);

                    if ($matches) {
                        $this->matchRoute = $this->route->routes[$method][$i];
                    }

                }

            }

            if ($withParams) {
                $params = $this->parseParameters($this->matchRoute, $requestUri);

                if ($params) {

                    foreach ($params as $key => $value) {
                        $this->matchRoute['params'][$key] = $value;
                    }

                }
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    private function createRegex() {
        foreach ($this->route->routes as $method => &$routes) {
            foreach ($routes as &$route) {
                $routeUrl = $route['uri'];

                $regex = (strpos($routeUrl, $this->paramModifiers[0]) === false) ? null :
                    sprintf
                    (
                        static::PARAMETERS_REGEX_FORMAT,
                        $this->paramModifiers[0],
                        $this->paramOptionalSymbol,
                        $this->paramModifiers[1]
                    );

                $urlRegex = '';
                $parameters = [];

                if ($regex === null || (bool)preg_match_all('/' . $regex . '/u', $routeUrl, $parameters) === false) {
                    $urlRegex = preg_quote($routeUrl, '/');
                } else {

                    foreach (preg_split('/((\-?\/?)\{[^}]+\})/', $routeUrl) as $key => $t) {

                        $regex = '';

                        if ($key < \count($parameters[1])) {

                            $name = $parameters[1][$key];

                            /* If custom regex is defined, use that */
//                            if (isset($this->where[$name]) === true) {
//                                $regex = $this->where[$name];
//                            } else if ($parameterRegex !== null) {
//                                $regex = $parameterRegex;
//                            } else {
                            $regex = $this->defaultParameterRegex ?? static::PARAMETERS_DEFAULT_REGEX;
//                            }

                            $regex = sprintf('((\/|\-)(?P<%2$s>%3$s))%1$s', $parameters[2][$key], $name, $regex);
                        }

                        $urlRegex .= preg_quote($t, '/') . $regex;

                    }
                }
                $route['uriRegex'] = $urlRegex;
            }
        }
    }

    public function parseParameters($route, $url, $parameterRegex = null) {
        $regex = (strpos($route['uri'], $this->paramModifiers[0]) === false) ? null :
            sprintf
            (
                static::PARAMETERS_REGEX_FORMAT,
                $this->paramModifiers[0],
                $this->paramOptionalSymbol,
                $this->paramModifiers[1]
            );

        // Ensures that host names/domains will work with parameters
        $url = '/' . ltrim($url, '/');
        $urlRegex = '';
        $parameters = [];

        if ($regex === null || (bool)preg_match_all('/' . $regex . '/u', $route['uri'], $parameters) === false) {
            $urlRegex = preg_quote($route['uri'], '/');
        } else {

            foreach (preg_split('/((\-?\/?)\{[^}]+\})/', $route['uri']) as $key => $t) {

                $regex = '';

                if ($key < \count($parameters[1])) {

                    $name = $parameters[1][$key];

                    /* If custom regex is defined, use that */
                    if (isset($this->where[$name]) === true) {
                        $regex = $this->where[$name];
                    } else if ($parameterRegex !== null) {
                        $regex = $parameterRegex;
                    } else {
                        $regex = $this->defaultParameterRegex ?? static::PARAMETERS_DEFAULT_REGEX;
                    }

                    $regex = sprintf('((\/|\-)(?P<%2$s>%3$s))%1$s', $parameters[2][$key], $name, $regex);
                }

                $urlRegex .= preg_quote($t, '/') . $regex;
            }
        }

        $urlRegex = $route['uriRegex'];

        if (trim($urlRegex) === '' || (bool)preg_match(sprintf($this->urlRegex, $urlRegex), $url, $matches) === false) {
            return null;
        }

        $values = [];

        if (isset($parameters[1]) === true) {

            /* Only take matched parameters with name */
            foreach ((array)$parameters[1] as $name) {
                $values[$name] = (isset($matches[$name]) === true && $matches[$name] !== '') ? $matches[$name] : null;
            }
        }

        return $values;
    }
}