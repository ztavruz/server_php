<?php


namespace Engine\Collector;

use App\Controller\UserController;
use App\Controller\FilesController;
use App\Controller\TaskController;
use App\Helper\Tester;
use App\Repository\FileRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Service\FileService;
use App\Service\TaskService;
use App\Storage\SessionStorage;
use Engine\Container\Container;
use Engine\Router\Router;
use App\Database\Connection;

class ContainerCollector
{
    public $container;
    public $connection;

    public function __construct()
    {
        $this->container = new Container();
        $this->connection = new Connection();
        $this->createCollector();
    }




    public function createCollector()
    {
        //connect
        $container = $this->container;

        $container->set(Connection::class, function(Container $container){
            return new Connection();
        });
        //user
        $container->set(UserRepository::class, function (Container $container) {
            return new UserRepository($container->get(Connection::class));
        });
        $container->set(UserService::class, function (Container $container) {
            return new UserService($container->get(UserRepository::class),
                $container->get(SessionStorage::class));
        });
        $container->set(UserController::class, function (Container $container) {
            return new UserController($container->get(UserService::class));
        });

    }

}