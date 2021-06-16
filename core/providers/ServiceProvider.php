<?php


namespace application\core\providers;


use application\core\Application;
use application\app\providers;

class ServiceProvider {
    protected string $rootFolder;
    protected array $stack = [];

    public function loader() {
        $providersFolder = Application::$ROOT_PATH . '/app/Providers';

        if (is_dir($providersFolder)) {
            $providers = array_diff(scandir($providersFolder), array('.', '..'));

            foreach ($providers as $provider) {

                $class = 'application\app\providers\\' . pathinfo($provider, PATHINFO_FILENAME);

                $instance = new $class();
                $instance->boot();
            }

        }
    }

    protected function boot() {

        foreach ($this->stack as $file) {
            //print_r($file . PHP_EOL);
            // Check if file exists

            $path = Application::$ROOT_PATH . '/' . $this->rootFolder . '/' . $file;
            $this->load($path);

        }
    }

    private function load($path) {
        if (file_exists($path)) {
            include_once $path;
        }
    }
}