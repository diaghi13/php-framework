<?php

use application\core\Application;

function loadRoutes() {
    $directory = Application::$ROOT_PATH . "/routes";
    $files = array_diff(scandir($directory), ['.', '..']);
    foreach ($files as $file) {
        include_once "$directory/$file";
    }
}
