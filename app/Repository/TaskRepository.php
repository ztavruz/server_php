<?php

namespace App\Repository;

use App\Database\Connection;
use App\Entity\Task;
use App\Entity\User;
use RedBeanPHP\R as R;

class TaskRepository
{
    private $tableName;
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = new $connection;
        $this->tableName = "tasks";
    }

    public function add(Task $task)
    {
        $table = R::dispense($this->tableName);
        $table->name = $task->getName();
        $table->content = $task->getContent();
        $table->creator = $task->getCreator()->getId();
        $table->executor = $task->getExecutor()->getId();
        $table->status = $task->getStatus();
        $table->image = $task->getImage()->getId();
        R::store($table);


        return $task;
    }

    public function findOneBy($field, $value)
    {
        $data = R::findOne ($this->tableName, "{$field} = ?", array($value));
        $data = $this->convertToObject($data);
        return $data;
    }

    public function convertToObject($data)
    {
        $task = new Task();

        $task->setId($data["id"]);
        $task->setName($data["name"]);
        $task->setContent($data["content"]);
        $task->setCreator($data["creator"]);
        $task->setExecutor($data["executor"]);
        $task->setImage($data["image"]);
        $task->setStatus($data["status"]);

        return $task;
    }

    public function findList($limit, $offset): array
    {
        $number = 0;
        $listTasks = [];

        for($i = 0; $i < $limit; $i++)
        {
            $number++;
            $bind = [
                "number" => $number
            ];

            $task = R::findOne($this->tableName, "id = :number", $bind );

            $creatorId  = $task["creator"];
            $executorId = $task["executor"];
            $imageId    = $task["image"];

            $userRepository = new UserRepository(new Connection());
            $creator = $userRepository->findOneBy( "id", $creatorId );


            $executor = $userRepository->findOneBy( "id", $executorId );

            $fileRepository = new FileRepository(new Connection());
            $image = $fileRepository->findOneBy( "id", $imageId );

            $task["creator"] = $creator;
            $task["executor"] = $executor;
            $task["image"] = $image;

            $task = $this->convertToObject($task);



            $listTasks[] = $task;
        }

        return $listTasks;
    }
}