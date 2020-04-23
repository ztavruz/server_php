<?php


namespace App\Repository;


use App\Database\Connection;
use App\Entity\File;
use App\Entity\User;
use RedBeanPHP\R as R;

class   FileRepository
{
    private $connection;
    private $tableName;

    public function __construct(Connection $connection)
    {
        $this->connection = new $connection;
        $this->tableName = "files";
    }

    public function add(File $file)
    {

        $table = R::dispense($this->tableName);
        $table->name      = $file->getName();
        $table->type      = $file->getFileType();
        $table->extension = $file->getExtension();
        $table->path      = $file->getPath();
        $table->size      = $file->getSize();

        R::store($table);

        $data = $this->findOneBy("name", "{$file->getName()}");
        $file = $this->convertToObject($data);

        return $file;
    }

    public function findOneBy($field, $value)
    {
        $data = R::findOne ($this->tableName, "{$field} = ?", array($value));
        $data = $this->convertToObject($data);

        return $data;
    }

    public function convertToObject($data)
    {
        $file = new File();
        $file->setId($data['id']);
        $file->setName($data['name']);
        $file->setFileType($data['type']);
        $file->setExtension($data['extension']);
        $file->setSize($data['size']);
        $file->setPath($data['path']);

        return $file;
    }

}