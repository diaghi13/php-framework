<?php


namespace application\app\http\controllers;


use application\app\models\User;
use application\core\Application;
use application\core\http\Controller;

class HomeController extends Controller {

    public function index(): string {
        $user = new User();
        if (!Application::$app->isGuest()) {
            $user = User::findOne([User::primaryKey() => Application::$app->session->get('user')]);
        }
        return view('home', ['user' => $user]);
    }
}