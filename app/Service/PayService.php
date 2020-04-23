<?php


namespace App\Service;

use App\Entity\Pay;
use App\Repository\PayRepository;
use App\Dto\Pay\PayDto;
use App\Dto\Pay\ConfirmDto;
use App\Service\WinService;

use App\Helper\Logger;

class PayService
{
    private $payRepository;

    private $logger;


    public function __construct(PayRepository $payRepository)
    {
        $this->payRepository = $payRepository;

        $this->logger = new Logger(false );

        //        $this->logger->flyLog();
    }

    public function create(PayDto $dto)
    {

        $pay = new Pay();
        $pay->setUserId($dto->userId);
        $pay->setSum($dto->sum);

        $pay = $this->payRepository->create($pay);

        return $pay;
    }

    public function confirm(ConfirmDto $dto)
    {
        $confirm = new Pay();
        $confirm->setAmount($dto->amount);
        $confirm->setLabel($dto->label);
        $confirm = $this->payRepository->confirm($confirm);


        $fullPack = new Pay();
        $fullPack->setAmount($dto->amount);
        $fullPack->setLabel($dto->label);

        $this->logger->flyLog("Запущена процедура определения полноты стека ..");
        $fullPack = $this->payRepository->fullPack($fullPack);

        if($fullPack != 0){

            $this->logger->flyLog("Стек " . $dto->amount ."-ки ЗАПОЛНЕН");
            $confirm["fullPack"] = $fullPack;
            $winnerService = new WinService();

            $this->logger->flyLog("Запущена процедура определения ПОБЕДИТЕЛЯ ..");
            $winner = $winnerService->winGenerate($fullPack);


            $this->logger->flyLog("Запущена процедура очистки ". $dto->amount ."-го стека ..");
            $this->payRepository->clearTable($dto);

        }else{
            $this->logger->flyLog("Стек " . $dto->amount . "-ки не заполнен");
        }

    }
}