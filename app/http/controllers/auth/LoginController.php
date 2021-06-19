<?php


namespace application\app\http\controllers\auth;


use application\app\middlewares\AuthMiddleware;
use application\app\models\auth\LoginUser;
use application\core\Application;
use application\core\http\Controller;
use application\core\http\Request;
use application\core\support\facades\DB;

class LoginController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->registerMiddleware(new AuthMiddleware(['logout']));
    }

    public function login() {
        $user = new LoginUser();
        return view('auth/login', ['model' => $user]);
    }

    public function handleLogin(Request $request) {
        $user = new LoginUser();
        $user->loadData($request->getBody());
        if ($user->validate() && $user->login()) {
            Application::$app->response->redirect('/');
        }
        return view('auth/login', ['model' => $user]);
    }

    public function logout() {
        Application::$app->logout();
        Application::$app->response->redirect('/');
    }
}
