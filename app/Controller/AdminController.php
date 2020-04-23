<?php


namespace App\Controller;

use App\Service\AdminService;

class AdminController
{
    private $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;

    }

    public function getAllUsers()
    {
        $allUsers = $this->adminService->getAllUsers();

        echo json_encode($allUsers);
    }
}