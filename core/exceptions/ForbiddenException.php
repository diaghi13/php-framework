<?php


namespace application\core\exceptions;


class ForbiddenException extends \Exception {

    public function __construct() {
        parent::__construct();
        $this->message = 'You are not authorize to access this page';
        $this->code = 403;
    }
}