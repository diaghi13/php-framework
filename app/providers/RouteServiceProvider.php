<?php

namespace application\app\providers;

use application\core\providers\ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

    protected string $rootFolder = 'routes';

    public array $routes = [
        'web.php',
        //'api.php',
        //'auth.php',
    ];

    public function boot() {
        //$this->stack = $this->routes;
        $this->stack = [
            'web.php'
        ];
        parent::boot();
    }

}