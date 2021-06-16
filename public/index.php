<?php

use application\app\http\controllers\HomeController;
use application\core\Application;
use application\core\support\facades\Route;

require_once __DIR__ . "/../vendor/autoload.php";

$app = new Application(dirname(__DIR__));

$app->run();
