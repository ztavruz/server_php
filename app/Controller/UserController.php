<?php


namespace App\Controller;


use App\Service\UserService;
use App\Dto\LoginDto;
use App\Dto\SignupDto;

class UserController
{
    private $userService;
    private $jsonData;
    private $jsonPost;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

        if(array_key_exists("user", $_POST))
        {
            $this->jsonPost = json_decode($_POST["user"], true);
//            $this->jsonPost = json_decode($_POST, true);
        }

    }

    public function signup()
    {
        $dto = new SignupDto();
        $data = $this->jsonPost;

        $dto->email = $data['email'];
        $dto->password = $data['password'];
        $dto->login = $data['login'];


        $response = $this->userService->signup($dto);
        echo json_encode($response);
    }

    public function login()
    {
        $dto = new LoginDto();
        $data = $this->jsonPost;

        $dto->email = $data['email'];
        $dto->password = $data['password'];

        $response = $this->userService->login($dto);
        echo json_encode($response);

    }

    public function showAll(){

        $users = $this->userService->showAll();

        echo json_encode($users);
    }
}