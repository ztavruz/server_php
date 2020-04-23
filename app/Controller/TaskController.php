<?php


namespace App\Controller;


use App\Service\Task\TaskDto;
use App\Service\Task\TaskService;

class TaskController
{
    private $service;
    private $jsonData;

    public function __construct(TaskService $service)
    {
        $this->service = $service;
        $this->jsonData = json_decode(file_get_contents("php://input"), true);

    }

    public function create()
    {
        $dto = new TaskDto();
        $dto->name = $this->jsonData['name'];
        $dto->content = $this->jsonData['content'];
        $dto->creatorId = $this->jsonData['creatorId'];
        $dto->executorId = $this->jsonData['executorId'];
        $dto->image = $this->jsonData['imageId'] ?? null;

        $task = $this->service->create($dto);

        $response = json_encode([
            'id' => $task->getId(),
            'name' => $task->getName(),
            'content' => $task->getContent(),
            'creator' => [
                'id' => $task->getCreator()->getId(),
                'login' => $task->getCreator()->getLogin(),
                'status' => $task->getCreator()->getStatus()
            ],
            'executor' => [
                'id' => $task->getExecutor()->getId(),
                'login' => $task->getExecutor()->getLogin(),
                'status' => $task->getExecutor()->getStatus()
            ],
            'image' => $task->getImage() == null ? null : [
                'id' => $task->getImage()->getId(),
                'name' => $task->getImage()->getName(),
                'path'=> $task->getImage()->getPath(),
                'type' => $task->getImage()->getFileType(),
                'extension' => $task->getImage()->getExtension()
            ]
        ]);

        return $response;
    }

    public function list()
    {
        $dto = new TaskDto();

        $dto->limit = $_GET["limit"] ?? 10;
        $dto->offset = $_GET["offser"] ?? 0;

        $response = $this->service->findList($dto);



//        return json_encode($response);

        var_dump(json_encode($response));
    }
}