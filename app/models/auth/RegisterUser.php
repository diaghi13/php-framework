<?php


namespace application\app\models\auth;


use application\app\models\User;

class RegisterUser extends User {
    public string $passwordConfirmation;

    public function rules():array {
        return [
            'firstName' => [self::RULE_REQUIRED],
            'lastName' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN_LENGTH, 'min'=> 8]],
            'passwordConfirmation' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
        ];
    }

    public function labels(): array {
        return array_merge(
            parent::labels(),
            ['passwordConfirmation' => 'Password confirmation']
        );
    }
}