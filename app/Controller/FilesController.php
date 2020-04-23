<?php
namespace App\Controller;

use App\Service\File\FileDto;
use App\Service\File\FileService;

class FilesController
{
    public $service;

    public function __construct(FileService $service)
    {
        $this->service = $service;
    }

    public function upload()
    {
        $file = $_FILES['file'];
        $dto = new FileDto();

        $dto->size     = $file['size'];
        $dto->name     = $file['name'];
        $dto->tmp      = $file['tmp_name'];
        $dto->error    = $file['error'];
        $dto->fileType = $file['type'];
        $dto->uploadDir = dirname(__DIR__, 2) . "/uploads";

        $file = $this->service->upload($dto);

        $response = json_encode([
            "id" => $file->getId(),
            "name" => $file->getName(),
            "extension" => $file->getExtension(),
            "path" => $file->getPath()
        ]);

        return $response;
    }

    public function display()
    {
        $dto = new FileDto();
        $dto->id = $_GET['id'];

        $file = $this->service->getOne($dto);

        header("Content-Type:" . $file->getFileType());

        $display = file_get_contents($file->getPath());
//        var_dump($file->getPath());
        echo $display;
    }
}