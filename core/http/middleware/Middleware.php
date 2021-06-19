<?php


namespace app\core\http\middleware;


use app\core\http\Request;
use Closure;

abstract class Middleware implements IMiddleware {

    /**
     * @param Request $request
     * @return Request
     */
    abstract public function handle(Request $request): Request;
}