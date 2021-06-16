<?php


namespace application\core\exceptions;


use Throwable;

class RequestException extends \Exception{
    public $message = 'Bad request';
    public $code = 500;

    public function __construct($message = "Bad request", $code = 500, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}