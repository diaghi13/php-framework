<?php


namespace application\core\database;


use application\core\Application;
use JetBrains\PhpStorm\Pure;

abstract class Model {
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN_LENGTH = 'min';
    public const RULE_MAX_LENGTH = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';
    public const RULE_UNIQUE_QUERY = 'unique_in_table';
    public const RULE_UNIQUE_EXCEPT_SELF = 'unique_not_self';
    public const RULE_PASSWORD = 'password';

    public function loadData($data) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
        return $this;
    }

    public function labels(): array {
        return [];
    }

    #[Pure] public function getLabel(string $property) {
        return $this->labels()[$property] ?? $property;
    }

    abstract public function rules(): array;

    //protected array $notVisible = ['notVisible'];

    /*public function __debugInfo(): ?array {
        $allProps = get_object_vars($this);
        $show = [];
        if (!empty($this->notVisible)) {
            foreach ($allProps as $key => $value) {
                if (!in_array($key, $this->notVisible)) {
                    $show[$key] = $value;
                }
            }
        }
        return $show;
    }*/

    protected array $errors = [];

    public function validate(): bool {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute} ?? '';
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorByRule($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL) && $value) {
                    $this->addErrorByRule($attribute, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN_LENGTH && strlen($value) < $rule['min'] && $value) {
                    $this->addErrorByRule($attribute, self::RULE_MIN_LENGTH, ['min' => $rule['min']]);
                }
                if ($ruleName === self::RULE_MAX_LENGTH && strlen($value) > $rule['max'] && $value) {
                    $this->addErrorByRule($attribute, self::RULE_MAX_LENGTH, ['max' => $rule['max']]);
                }
                if ($ruleName === self::RULE_MATCH) {
                    $match = $this->{$rule['match']} ?? '';
                    if ($value !== $match) {
                        $this->addErrorByRule($attribute, self::RULE_MATCH, $rule);
                    }
                }
                if ($ruleName === self::RULE_UNIQUE && $value) {
                    $className = $rule['class'];
                    $uniqueAttribute = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    $sth = Application::$app->database->prepare("SELECT * FROM $tableName WHERE $uniqueAttribute = :attr");
                    $sth->bindValue(":attr", $value);
                    $sth->execute();
                    $record = $sth->fetchObject();
                    if ($record) {
                        $this->addErrorByRule($attribute, self::RULE_UNIQUE, ['field' => $attribute]);
                    }
                }
                if ($ruleName === self::RULE_PASSWORD && $value) {
                    if ($value) {
                        if (!preg_match('/[A-Z]/', $value)) {
                            $this->addErrorByRule($attribute, self::RULE_PASSWORD);
                        }
                        if (!preg_match('/[a-z]/', $value)) {
                            $this->addErrorByRule($attribute, self::RULE_PASSWORD);
                        }
                        if (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $value)) {
                            $this->addErrorByRule($attribute, self::RULE_PASSWORD);
                        }
                        if (!preg_match('/[!£$%&@€#§*+]/', $value)) {
                            $this->addErrorByRule($attribute, self::RULE_PASSWORD);
                        }
                    }
                }
            }
        }

        return empty($this->errors);
    }

    private function addErrorByRule(string $attribute, string $rule, $params = []) {
        $message = $this->errorMessages()[$rule] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function addError(string $attribute, string $message) {
        $this->errors[$attribute][] = $message;
    }

    private function errorMessages(): array {
        return [
            self::RULE_REQUIRED => 'Campo richiesto',
            self::RULE_EMAIL => 'Inserire una mail valida',
            self::RULE_MIN_LENGTH => 'La lunghezza minima di questo campo è di {min}',
            self::RULE_MAX_LENGTH => 'La lunghezza massima di questo campo è di {max}',
            self::RULE_MATCH => 'Il campo deve essere uguale a {match}',
            self::RULE_UNIQUE => '{field} già inserito',
            self::RULE_PASSWORD => 'La password deve contenere almeno una lettere maiuscola, un numero e un carattere speciale'
        ];
    }

    public function hasErrors($attribute) {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute): string {
        return $this->errors[$attribute][0] ?? false;
    }
}