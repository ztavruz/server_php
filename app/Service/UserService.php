<?php

namespace App\Service;


use App\Dto\LoginDto;
use App\Dto\SignupDto;
use App\Repository\UserRepository;
use App\Security\Token\Token;
use App\Storage\SessionStorage;
use App\Entity\User;

class UserService
{
    private $repository;

    private $jwt;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
        $this->jwt = new Token();
    }

    //testable
    public function signup(SignupDto $dto)
    {
        $dto->status = "B";
        $response = $this->repository->create($dto);


        return $response;
    }

    public function login(LoginDto $dto)
    {

        $response = $this->repository->login($dto);

        return $response;
    }

    public function showAll()
    {
        $users = $this->repository->getAll();

        return $users;
    }





}