<?php


namespace application\app\models;


use application\core\database\DbModel;

class User extends DbModel {
    private const INACTIVE = 0;
    private const ACTIVE = 1;
    private const REMOVED = 2;

    public ?string $id;
    public ?string $username;
    public ?string $firstName;
    public ?string $lastName;
    public ?string $email;
    public ?string $password;
    public ?string $phoneNumber;
    public ?string $securityStamp;
    public ?string $status;

    public function rules(): array {
        return [
            'email' => [self::RULE_EMAIL],
            'password' => [self::RULE_PASSWORD]
        ];
    }

    public function labels(): array {
        return [
            'username' => 'Username',
            'firstName' => 'First name',
            'lastName' => 'Last name',
            'email' => 'Email',
            'password' => 'Password',
            'phoneNumber' => 'Phone number',
            'securityStamp' => 'Security Code',
            'status' => 'Status',
        ];
    }

    public function create(): string {
        return '...Creating new user...';
    }

    public static function tableName(): string {
        return 'user';
    }
}