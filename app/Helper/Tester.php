<?php


namespace App\Helper;


use App\Security\Token\Token;

class Tester
{
    public $token;

    public function __construct()
    {
        $this->token = "eyJhbGciOiJzaGEyNTYiLCJ0eXAiOiJKV1QiLCJlbmQiOjE1ODMzNTg4ODZ9.eyJpZCI6IjEiLCJsb2dpbiI6Inp0YXZydXoiLCJzdGF0dXMiOjJ9.ZGU3MjZkNjJlZDllODMwMzVmNWM2NGEzNzZkYzA3MWEyYzcxYzc5MWJkNTY5OTM1MDBlZDk2NjUxNmQzMDU3OA==";

    }

    public function jwtencode()
    {
        $jwt = new Token();
        $jwt->encode();
    }

    public function jwtdecode()
    {
        $jwt = new Token();
        $result = $jwt->decode($this->token);

        var_dump($result);
    }

    public function jwtverify()
    {
        $jwt = new Token();
        $result = $jwt->verify($this->token);

        var_dump($result);
    }
}