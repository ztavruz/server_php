<?php

namespace App\Repository;

use RedBeanPHP\R as R;
use App\Database\Connection;
use App\Dto\WinnerDto;

use App\Helper\Logger;

class WinRepository
{
    private $connection;
    private $tableName;
    private $error;
    private $logger;

    public function __construct()
    {
        $this->connection = new Connection();
        $this->tableName = "winners";
        $this->logger = new Logger(false);

//        $this->logger->flyLog();
    }

    public function addWinner(WinnerDto $dto)
    {

        $prepareWinTable = "prepare" . $this->tableName . $dto->stavka;
        $prepareExist = $this->findOneBy($prepareWinTable, 'user_id', $dto->userId);

        if (!$prepareExist) {
            $this->logger->flyLog("Пользователь не числится в призовых местах текущего банка");
            $table = R::dispense($prepareWinTable);
            $table->user_id             = $dto->userId;
            $table->time_point_prepare  = time();
            $table->stavka              = $dto->stavka;
            $table->prize_fond          = $dto->prizeFond;
            R::store($table);
            $this->logger->flyLog("Сохраняю данные победителя в таблицу " .$prepareWinTable. " до момента выплаты...");

        }elseif ($prepareExist){
            $this->logger->flyLog("Этот пользователь уже имеет призовое место в текущем банке");
            $table = R::dispense($prepareWinTable);
            $table->user_id             = $dto->userId;
            $table->time_point_prepare  = time();
            $table->stavka              = $dto->stavka;
            $table->prize_fond          = $dto->prizeFond;
            R::store($table);
            $this->logger->flyLog("Сохраняю данные победителя в таблицу " .$prepareWinTable. " до момента выплаты...");
        }

    }

    public function findOneBy( $table, $field, $value)
    {

        $data = R::findOne($table , "{$field} = ?", array($value));
        if ($data) {
            return $data;
        } else {
            return null;
        }

    }


}