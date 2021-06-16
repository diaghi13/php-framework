<?php

use application\app\http\controllers\auth\LoginController;
use application\app\http\controllers\HomeController;
use application\app\http\controllers\auth\RegisterController;
use application\core\support\facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::get('/login', [LoginController::class, 'login']);
Route::post('/login', [LoginController::class, 'handleLogin']);

Route::get('/register', [RegisterController::class, 'register']);
Route::post('/register', [RegisterController::class, 'handleRegister']);

Route::get('/about', function (){
    return "About";
});

Route::get('/{token}/test/{id}', function ($token, $id){
    return "$token in test composed uri with id $id" ;
});

Route::get('/user/{userId}', function ($userId) {
    return "User: $userId";
});
