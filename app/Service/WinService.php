<?php


namespace App\Service;


use App\Repository\WinRepository;
use App\Dto\WinnerDto;
use App\Helper\Logger;

class WinService
{
    private $repository;
    private $logger;


    public function __construct()
    {
        $this->repository = new WinRepository();
        $this->logger = new Logger(false);

//        $this->logger->flyLog();
    }
    public function winGenerate(array $fullPack)
    {
        $min = 1;
        $max = count($fullPack);
        $number = mt_rand($min, $max) - 1;
        $winnerId = $fullPack[$number];

        $prizeFond = 0;
        $stavka    = 0;

        switch ($max){
            case 10 :
                $stavka    = 10;
                $prizeFond = 100;
                break;
            case 20 :
                $stavka    = 50;
                $prizeFond = 1000;
                break;
            case 40 :
                $stavka    = 250;
                $prizeFond = 10000;
                break;
            case 100 :
                $stavka    = 1000;
                $prizeFond = 100000;
                break;
            case 200 :
                $stavka    = 5000;
                $prizeFond = 1000000;
                break;
            case 400 :
                $stavka    = 25000;
                $prizeFond = 10000000;
                break;
        }

        $dto = new WinnerDto();
        $dto->userId = $winnerId;
        $dto->stavka = $stavka;
        $dto->prizeFond = $prizeFond;

        $this->logger->flyLog("Данные победителя направлены в репозиторий");
        $winner = $this->repository->addWinner($dto);


        return $winner;
    }
}