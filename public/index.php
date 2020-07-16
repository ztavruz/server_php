<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");

ini_set('display_errors', 1);
error_reporting(E_ALL);


require_once dirname(__DIR__) . "/vendor/autoload.php";
use Engine\Container\Container;
use Engine\Router\Router;
use App\Database\Connection;

use App\Controller\UserController;
use App\Controller\FilesController;
use App\Controller\TaskController;
use App\Controller\PayController;
use App\Controller\AdminController;


use App\Repository\FileRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Repository\PayRepository;
use App\Repository\WinRepository;
use App\Repository\AdminRepository;

use App\Service\UserService;
use App\Service\FileService;
use App\Service\TaskService;
use App\Service\PayService;
use App\Service\WinService;
use App\Service\AdminService;

use App\Storage\SessionStorage;
use App\Helper\Tester;


$router = new Router();
$router->add("/api/signup",         UserController::class,  'signup', "C");
$router->add("/api/login",          UserController::class,  "login");
$router->add("/api/user/showAll",   UserController::class,  "showAll");
$router->add("/files/upload",       FilesController::class, "upload");
$router->add("/files/display",      FilesController::class, "display");
$router->add("/task/create",        TaskController::class,  "create");
$router->add("/task/list",          TaskController::class,  "list");

$router->add("/api/payRegister",    PayController::class,   "create");
//$router->add("/api/payConfirm",       PayController::class,   "confirmINTERKASSA");
//$router->add("/api/testPay",          PayController::class,   "testPay");
$router->add("/api/payConfirm",     PayController::class,   "confirmYANDEX");
$router->add("/api/payMessage",     PayController::class,   "message");


$router->add("/api/admin/getAllUsers", AdminController::class,   "getAllUsers");


$router->add("/api/tester/jwtencode",    Tester::class,          "jwtencode");
$router->add("/api/tester/jwtdecode",    Tester::class,          "jwtdecode");
$router->add("/api/tester/jwtverify",    Tester::class,          "jwtverify");
$router->add("/api/tester/question16",   Tester::class,          "question16");
$router->add("/api/tester/task17",       Tester::class,          "task17");

$container = new Container();

//connect
$container->set(Connection::class, function(Container $container){
    return new Connection();
});

//admin
$container->set(AdminRepository::class, function (Container $container) {
    return new AdminRepository($container->get(Connection::class));
});
$container->set(AdminService::class, function (Container $container) {
    return new AdminService($container->get(AdminRepository::class),
                            $container->get(UserRepository::class));
});
$container->set(AdminController::class, function (Container $container) {
    return new AdminController($container->get(AdminService::class));
});

//winner
$container->set(WinRepository::class, function (Container $container) {
    return new WinRepository($container->get(Connection::class));
});
$container->set(WinService::class, function (Container $container) {
    return new WinService($container->get(WinRepository::class));
});
$container->set(WinController::class, function (Container $container) {
    return new WinController($container->get(WinService::class));
});

//pay
$container->set(PayRepository::class, function (Container $container) {
    return new PayRepository($container->get(Connection::class));
});
$container->set(PayService::class, function (Container $container) {
    return new PayService($container->get(PayRepository::class));
});
$container->set(PayController::class, function (Container $container) {
    return new PayController($container->get(PayService::class));
});

//user
$container->set(UserRepository::class, function (Container $container) {
    return new UserRepository($container->get(Connection::class));
});
$container->set(UserService::class, function (Container $container) {
    return new UserService($container->get(UserRepository::class));
});
$container->set(UserController::class, function (Container $container) {
    return new UserController($container->get(UserService::class));
});

//files
$container->set(FileRepository::class, function(Container $container){
   return new FileRepository($container->get(Connection::class));
});
$container->set(FileService::class, function(Container $container){
    return new FileService($container->get(FileRepository::class));
});
$container->set(FilesController::class, function(Container $container){
    return new FilesController($container->get(FileService::class));
});

//task
$container->set(TaskRepository::class, function(Container $container){
   return new TaskRepository($container->get(Connection::class));
});
$container->set(TaskService::class, function(Container $container){
    return new TaskService($container->get(TaskRepository::class),
                            $container->get(UserRepository::class),
                             $container->get(FileRepository::class));
});
$container->set(TaskController::class, function(Container $container){
    return new TaskController($container->get(TaskService::class));
});

//tester
$container->set(Tester::class, function(Container $container){
    return new Tester();
});

//var_dump($_SERVER);
//var_dump($_SERVER["REDIRECT_URL"]);
$rout = $_SERVER["REQUEST_URI"];
$match = $router->match($rout);

//var_dump($rout);
//var_dump($match["_method"]);

$controller = $container->get($match["controller"]);
$method = $match['method'];

$response = $controller->$method();