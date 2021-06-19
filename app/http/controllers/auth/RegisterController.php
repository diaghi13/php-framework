<?php


namespace application\app\http\controllers\auth;


use application\app\middlewares\AuthMiddleware;
use application\app\models\auth\RegisterUser;
use application\core\Application;
use application\core\http\Controller;
use application\core\http\Request;

class RegisterController extends Controller {
    public function __construct() {
        parent::__construct();
        
    }

    public function register() {
        $user = new RegisterUser();
        return view('auth/register', ['model' => $user]);
    }

    public function handleRegister(Request $request) {
        $user = new RegisterUser();
        $user->loadData($request->getBody());
        if ($user->validate() && $user->create()) {
            $user->insert();
            Application::$app->response->redirect('/');
        }
        return view('auth/register', ['model' => $user]);
    }
}