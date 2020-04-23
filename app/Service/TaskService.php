<?php


namespace App\Service;


use App\Entity\Task;
use App\Repository\FileRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Entity\File;

class TaskService
{
    private $taskRepository;
    private $userRepository;
    private $fileRepository;

    public function __construct(TaskRepository $taskRepository, UserRepository $userRepository, FileRepository $fileRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->userRepository = $userRepository;
        $this->fileRepository = $fileRepository;
    }

    public function create(TaskDto $dto)
    {
        $creator  = $this->userRepository->findOneBy("id", $dto->creatorId);
        $executor = $this->userRepository->findOneBy("id", $dto->executorId);
        $image    = $this->fileRepository->findOneBy('id', $dto->image);

        $task = new Task();
        $task->setName($dto->name);
        $task->setContent($dto->content);
        $task->setCreator($creator);
        $task->setExecutor($executor);
        $task->setImage($image);

        $task = $this->taskRepository->add($task);

        return $task;
    }

    public function findList(TaskDto $dto): array
    {
        $tasks = $this->taskRepository->findList((int)$dto->limit, (int)$dto->offset);
        var_dump($tasks);
        $response = [];

        foreach ($tasks as $task)
        {
            $result = [
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
            ];

            $response[] = $result       ;
        }

        return $response;
    }


}