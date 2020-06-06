<?php

namespace App\Repository;


use App\Dto\Pay\ConfirmDto;
use App\Entity\Pay;
use App\Helper\PropertyObject;
use RedBeanPHP\R as R;
use App\Database\Connection;
use App\Helper\Logger;

class PayRepository
{
    private $connection;
    private $tableName;
    private $jwt;
    private $error;
    private $logger;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->tableName = "pays";
        $this->logger = new Logger(false );

//        $this->logger->flyLog();
    }

    public function create(Pay $pay)
    {

        $preparePayTable = "prepare" . $this->tableName . $pay->getSum();
        $completedPayTable = "completed" . $this->tableName . $pay->getSum();

        $prepareExist = R::findOne($preparePayTable, ' user_id = ?', [$pay->getUserId()]);
        $completedExist = R::findOne($completedPayTable, 'user_id = ?', [$pay->getUserId()]);



        if (!$prepareExist && !$completedExist) {
            $table = R::dispense($preparePayTable);
            $table->user_id = $pay->getUserId();
            $table->time_point_prepare = time();
            $table->sum = $pay->getSum();
            R::store($table);

            $bean = $this->findOneBy($preparePayTable, "user_id", $pay->getUserId());

            $pay = [
                "userId" => $bean["user_id"],
                "sum" => $bean["sum"],
                "timePoint" => $bean["time_point_prepare"]
            ];
            return $pay;

        } elseif ($prepareExist && !$completedExist) {

            $bean = $this->findOneBy($preparePayTable, "user_id", $pay->getUserId());

            $pay = [
                "userId" => $bean["user_id"],
                "timePoint" => $bean["time_point_prepare"],
                "prepareExist" => true,
                "sum" => $bean['sum']
            ];
            return $pay;

        } elseif (!$prepareExist && $completedExist) {

            $bean = $this->findOneBy($completedPayTable, "user_id", $pay->getUserId());

            $error = [
                "sum" => $bean->sum,
                "error" => "Дружище, ты уже принимаешь участие в этом банке! Один банк - один билет!",
            ];

            return $error;
        }

    }

    public function confirm(Pay $confirm)
    {

        $label = $confirm->getLabel();
        $preparePayTable = "prepare" . $this->tableName . $confirm->getAmount();
        $completedPayTable = "completed" . $this->tableName . $confirm->getAmount();


        $prepareExist = $this->findOneBy($preparePayTable, "time_point_prepare", $label);
        if ($prepareExist ) {
            $this->logger->flyLog("Данные найдены в таблице предзаказа...");
            $table = R::dispense($completedPayTable);
            $table->user_id = $prepareExist["user_id"];
            $table->time_point_prepare = $prepareExist["time_point_prepare"];
            $table->time_point_completed = time();
            $table->sum = $confirm->getAmount();
            R::store($table);
            $this->logger->flyLog("Данные сохранены в таблицу оплаты...");
            $id = $prepareExist["id"];
            $excessItem = R::load($preparePayTable, $id);
            R::trash($excessItem);
            $this->logger->flyLog("Данные удалены из таблицы предзаказа...");

            $completeExist["answer"] = $this->findOneBy($completedPayTable, "time_point_prepare", $label);
            $this->logger->flyLog("Копия данных отправлена в сервис для дальнейшей обработки...");
        }


    }

    public function fullPack(Pay $confirm)
    {
        $totalPaysCounter = 0;
        $completedPayTable = "completedpays" . $confirm->getAmount();

        switch ($confirm->getAmount()) {
            case 10:
                $totalPaysCounter = 10;
                break;
            case 50:
                $totalPaysCounter = 20;
                break;
            case 250:
                $totalPaysCounter = 40;
                break;
            case 1000:
                $totalPaysCounter = 100;
                break;
            case 5000:
                $totalPaysCounter = 200;
                break;
            case 25000:
                $totalPaysCounter = 400;
                break;
        }

        $result = R::getRow(
            "SELECT * FROM {$completedPayTable} WHERE `id` LIKE {$totalPaysCounter} LIMIT 1");

        if ($result) {

            $arrayParticipants = R::getCol("SELECT `user_id` FROM {$completedPayTable}");
            return $arrayParticipants;

        } else {
            $arrayParticipants = 0;
            return $arrayParticipants;
        }

    }

    public function clearTable(ConfirmDto $confirm)
    {
        $completedPayTable = "completed" . $this->tableName . $confirm->amount;
        R::wipe($completedPayTable);
        $this->logger->flyLog("Таблица ".$completedPayTable." очищена");
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

}