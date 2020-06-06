<?php


namespace App\Service;


use App\Repository\AdminRepository;
use App\Repository\UserRepository;

class AdminService
{
    private $adminRepository;
    private $userRepository;

    public function __construct(AdminRepository $adminRepository, UserRepository $userRepository)
    {
        $this->adminRepository = $adminRepository;
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        $allUsers = $allUsers = $this->userRepository->getAll();

        return $allUsers;
    }
}