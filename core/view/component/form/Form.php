<?php


namespace application\core\view\component\form;


class Form {
    public static function begin(string $action = "", string $method = "GET"): Form {
        echo sprintf('<form action="%s" method="%s">', $action, $method);
        return new Form();
    }

    public function end() {
        echo '</Form>';
    }

    public function inputField($model, $property) {
        return new InputField($model, $property);
    }
}