<?php


namespace application\core\exceptions;


class RouteNotFoundException extends \Exception {
    protected $message = "Route not found exception";
    protected $code = 404;
}