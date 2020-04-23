<?php


namespace App\Entity;


class User
{
    public const STATUS_ADMIN = "A";
    public const STATUS_USER = "B";
    public const STATUS_GUEST = "C";

    private $id;
    private $login;
    private $password;
    private $name;

    public $phone;
    public $email;
    public $bankCard;

    private $status;
    private $jwt;



    public function __construct()
    {
        $this->status = self::STATUS_USER;
    }

    public function verifyPassword($password)
    {
        return password_verify($this->password, $password);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getBankCard()
    {
        return $this->bankCard;
    }

    /**
     * @param mixed $bankCard
     */
    public function setBankCard($bankCard)
    {
        $this->bankCard = $bankCard;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getJwt()
    {
        return $this->jwt;
    }

    /**
     * @param mixed $jwt
     */
    public function setJwt($jwt)
    {
        $this->jwt = $jwt;
    }


}