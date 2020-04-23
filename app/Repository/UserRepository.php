<?php


namespace App\Repository;

use App\Database\Connection;
use App\Dto\LoginDto;
use App\Dto\SignupDto;
use App\Entity\User;
use App\Security\Token\Token;
use RedBeanPHP\R as R;


class UserRepository
{
    private $connect;
    private $tableName;
    private $jwt;
    private $response;

    public function __construct(Connection $connection)
    {
        $this->connect = $connection;
        $this->tableName = "users";
        $this->jwt = new Token();
    }

    public function create(SignupDto $dto)
    {
        $data = $this->findOneBy($this->tableName,"email", $dto->email);

        if($data == null){
            $table              = R::dispense($this->tableName);
            $table->login       = $dto->login;
            $table->password    = password_hash($dto->password, PASSWORD_DEFAULT);
            $table->email       = $dto->email;
            $table->status      = $dto->status;        //задается по умолчанию
            $table->jwt         = null;
            R::store($table);



            $bean = $this->findOneBy($this->tableName,"email", $dto->email);
            $payload = [
                "id" => $bean["id"],
                "email" => $bean["email"],
                "status" => $bean["status"]
            ];

            $bean->jwt = $this->jwt->encode($payload, 36000);
            R::store($bean);

            $user = $this->findOneBy($this->tableName,"email", $dto->email);

            $this->response["user"] = $user;
            $this->response["error"] = 0;
            return $this->response;

        }else{
            $this->response["error"] =  "Пользователь с таким email уже существует...";
            return $this->response;
        }

    }

    public function login(LoginDto $dto)
    {
        $data = $this->findOneBy($this->tableName, "email", $dto->email);


        if($data)
        {

            $user = new User();
            $user->setPassword($dto->password);
            $verify = $user->verifyPassword($data['password']);

            if($verify){

                $bean = $this->findOneBy($this->tableName, "email", $dto->email);

                $payload = [
                    "id" => $bean["id"],
                    "login" => $bean["login"],
                    "status" => $bean["status"]
                ];

                $bean->jwt = $this->jwt->encode($payload, 36000);
                R::store($bean);

                $user = $this->findOneBy($this->tableName, "email", $dto->email);

                $this->response["error"] = 0;
                $this->response["user"] = $user;

                return $this->response;

            }else{
                return $this->response["error"] = "Введен не правильный пароль...[Capslock]?";
                return $this->response;
            }
        }else{
            return $this->response["error"] = "Пользователь с таким email не найден...";
            return $this->response;
        }

    }

    public function getAll()
    {
        $data = R::getAll("SELECT * FROM $this->tableName ");

        return $data;
    }

    public function findOneBy($table, $field, $value, $typeData = "object")
    {

        $data = R::findOne($table, "{$field} = ?", array($value));
        if ($data) {
            return $data;
        } else {
            return null;
        }

    }

    public function convertToObject($data)
    {
        $user = new User();

        foreach ($data as $key => $value){
            $setter = "set". ucfirst($key);
            $user->$setter($value);
        }

        return $user;
    }










    //---------------OLD_CODE------------------//


    public function getOne($keyValue)
    {
        $item = null;
        $bind = $keyValue;
        foreach ($bind as $key => $keyValue) {
            $item = R::findOne ($this->tableName, "{$key} = ?", array($keyValue));
        }

        return $item;
    }

    public function delete($id)
    {

        $deleted = R::load($this->tableName, $id);
        R::trash($deleted);
    }


    public function deleteOne($pole, $value)
    {

        $books = R::find("{$this->tableName}", " '{$pole}' = ?", [$value]);

    }

    public function update($id, $pole, $newValue)
    {
        $table = R::load($this->tableName, $id);
        $table->$pole = $newValue;
    }

    //ready
    public function getCount($typeDate = "")
    {
//        var_dump($typeDate);
        if($typeDate == "")
        {
            $data = R::count($this->tableName);
        }else{
            $type = '';
            $val = '';
            foreach($typeDate as $key => $value)
            {
                $type = $key;
                $val = $value;
            }


            $data = R::count($this->tableName,"{$type} = ?" , array($val) );
        }

        return $data;
    }
}