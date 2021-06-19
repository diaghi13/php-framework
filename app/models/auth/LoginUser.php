<?php


namespace application\app\models\auth;


use application\app\models\User;
use application\core\Application;

class LoginUser extends User {

    public function rules():array {
        return [
            'emailAddress' => [self::RULE_REQUIRED],
            'passwordHash' => [self::RULE_REQUIRED],
        ];
    }

    public function login() {
        /** @var $user User */
        $user = self::findOne(['email_address' => $this->emailAddress]);
        if (!$user) {
            return false;
        }
        if (!password_verify($this->passwordHash, $user->passwordHash)){
            return false;
        }
        Application::$app->session->setFlash('login', 'You are logged in');
        return Application::$app->login($user);
    }
}