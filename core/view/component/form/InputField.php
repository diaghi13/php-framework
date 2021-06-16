<?php


namespace application\core\view\component\form;


use application\core\database\Model;

class InputField {
    public const TYPE_TEXT = 'text';
    public const TYPE_EMAIL = 'email';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';

    public Model $model;
    public string $property;
    public string $type;

    public function __construct($model, $property) {
        $this->model = $model;
        $this->property = $property;
        $this->type = self::TYPE_TEXT;
    }

    public function __toString(): string {
        if (!isset($this->model->{$this->property})) {
            $this->model->{$this->property} = '';
        }

        return sprintf('
            <div class="mb-3">
                <label for="%s" class="form-label">%s</label>
                <input type="%s" class="form-control%s" id="%s" name="%s" value="%s">
                <div class="invalid-feedback">
                  %s
                </div>
            </div >
        ',
            $this->property,
            $this->model->getLabel($this->property),
            $this->type,
            $this->model->hasErrors($this->property) ? ' is-invalid' : '',
            $this->property,
            $this->property,
            $this->model->{$this->property},
            $this->model->getFirstError($this->property)
        );
    }

    public function isPassword() {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function isEmail() {
        $this->type = self::TYPE_EMAIL;
        return $this;
    }

    public function isNumber() {
        $this->type = self::TYPE_NUMBER;
        return $this;
    }
}