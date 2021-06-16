<?php


namespace application\core;


use application\core\database\Database;
use application\core\http\Controller;
use application\core\http\Request;
use application\core\http\Response;
use application\core\providers\ServiceProvider;
use application\core\router\Router;
use application\core\session\Session;
use application\core\view\View;
use Dotenv\Dotenv;

class Application {
    public static Application $app;
    public static string $ROOT_PATH;

    public Request $request;
    public Response $response;
    public ServiceProvider $serviceProvider;
    public Router $router;

    public ?Controller $controller = null;
    public Session $session;

    public Dotenv $dotEnv;
    public Database $database;

    public View $view;

    public function __construct($directory) {
        self::$app = $this;
        self::$ROOT_PATH = $directory;

        $this->dotEnv = Dotenv::createImmutable(self::$ROOT_PATH);
        $this->dotEnv->load();

        include_once self::$ROOT_PATH . "/core/helpers/router.php";
        include_once self::$ROOT_PATH . "/core/helpers/utils.php";

        $this->request = new Request();
        $this->response = new Response();

        $this->router = new Router($this->request, $this->response);

        $this->view = new View();
        $this->session = new Session();

        //$this->serviceProvider = new ServiceProvider();
        //$this->serviceProvider->loader();

        loadRoutes();

        if (env('DB_NAME')) {
            $this->database = new Database([
                'dbType' => env('DB_TYPE'),
                'dbHost' => env('DB_HOST'),
                'dbPort' => env('DB_PORT'),
                'dbName' => env('DB_NAME'),
                'user' => env('DB_USER'),
                'password' => env('DB_PASSWORD')
            ]);
        }
    }

    public function run() {
        try {
            echo $this->router->resolve();
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }
}