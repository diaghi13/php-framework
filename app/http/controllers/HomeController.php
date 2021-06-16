<?php


namespace application\app\http\controllers;


use application\core\http\Controller;

class HomeController extends Controller {

    public function index(): string {
        return view('home', ['user' => 'Davide']);
    }
}