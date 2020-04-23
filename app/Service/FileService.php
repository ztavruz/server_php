<?php

namespace App\Service;

use App\Entity\File;
use App\Repository\FileRepository;

class FileService
{
    private $repository;

    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function upload(FileDto $dto)
    {
        if ($dto->error != UPLOAD_ERR_OK)
        {
            throw new \DomainException("Ошибка при загрузке файла(Cannot upload file)");
        }

        $extension = pathinfo($dto->name, PATHINFO_EXTENSION);

        $name = uniqid() . $extension;

        $path = "$dto->uploadDir/$name";

        if(!move_uploaded_file("{$dto->tmp}", "{$path}"))
        {
            throw new \DomainException("Не могу загрузить файл(Cannot upload file)");
        }

        $file = new File();
        $file->setName($name);
        $file->setFileType($dto->fileType);
        $file->setExtension($extension);
        $file->setSize($dto->size);
        $file->setPath($path);

        $file = $this->repository->add($file);

        return $file;
    }

    public function getOne(FileDto $dto)
    {

        $file = $this->repository->findOneBy("id", $dto->id);

        if($file != null){

            return $file;
        }

    }


}