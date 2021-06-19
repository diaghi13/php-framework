<?php


namespace application\core\http\middleware;


use application\core\http\Request;
use Closure;

abstract class Middleware implements IMiddleware {
    protected array $actions = [];

    public function __construct(array $actions = []) {
        $this->actions = $actions;
    }

    /**
     * @param Request $request
     * @return Request
     */
    abstract public function handle(Request $request): Request;
}