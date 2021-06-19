<?php


namespace app\core\http\middleware;


use app\core\http\Request;
use Closure;

interface IMiddleware {
    /**
     * @param Request $request
     * @return mixed
     */
    //public function handle(Request $request, Closure $next);
    public function handle(Request $request): Request;
}