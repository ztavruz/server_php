<?php


namespace App\Dto\Pay;


class ConfirmDto
{
    public $amount;
    public $label;
    public $datetime;
}


//operation_id = 904035776918098009
//notification_type = p2p-incoming
//datetime = 2014-04-28T16:31:28Z
//sha1_hash = 8693ddf402fe5dcc4c4744d466cabada2628148c
//sender = 41003188981230
//codepro = false
//currency = 643
//amount = 0.99
//withdraw_amount = 1.00
//label = YM.label.12345
//lastname = Иванов
//firstname = Иван
//fathersname = Иванович
//zip = 125075
//city = Москва
//street = Тверская
//building = 12
//suite = 10
//flat = 10
//phone = +79253332211
//email = adress@yandex.ru

//Пример того же уведомления при использовании протокола HTTP:
//operation_id = 904035776918098009
//notification_type = p2p-incoming
//datetime = 2014-04-28T16:31:28Z
//sha1_hash = 8693ddf402fe5dcc4c4744d466cabada2628148c
//sender = 41003188981230
//codepro = false
//currency = 643
//amount = 0.99
//withdraw_amount = 1.00
//label = YM.label.12345