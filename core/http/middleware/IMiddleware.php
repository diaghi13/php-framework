<?php


namespace application\core\http\middleware;


use application\core\http\Request;
use Closure;

interface IMiddleware {
    /**
     * @param Request $request
     * @return mixed
     */
    //public function handle(Request $request, Closure $next);
    public function handle(Request $request): Request;
}