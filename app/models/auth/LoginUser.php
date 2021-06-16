<?php


namespace application\app\models\auth;


use application\app\models\User;

class LoginUser extends User {

    public function rules():array {
        return [
            'email' => [self::RULE_REQUIRED],
            'password' => [self::RULE_REQUIRED],
        ];
    }

    public function login() {
        return 'Login in';
    }
}