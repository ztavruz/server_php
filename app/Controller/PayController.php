<?php


namespace App\Controller;


use App\Dto\Pay\PayDto;
use App\Dto\Pay\ConfirmDto;
use App\Helper\Logger;
use App\Service\PayService;

use App\Helper\Postman;

class PayController
{
    private $payService;
    private $arrayPost;

    private $logger;

    public function __construct(PayService $payService)
    {
        $this->payService = $payService;
        $this->logger = new Logger(false);

        if (isset($_POST["pay"])) {
            $this->arrayPost = json_decode($_POST["pay"], true);
        } elseif (isset($_POST['amount']) && isset($_POST['label'])) {
            $this->arrayPost = $_POST;
        } else {
            $this->logger->flyLog("В массиве POST отсутствуют ТРЕБУЕММЫЕ данные. Возможно запущен режим отладки");
        }

//        file_put_contents("./tested222.php", json_encode($_POST));
//        file_put_contents("./tested333.php", json_encode($_SERVER));
//        $this->logger->flyLog();
    }


    public function create()
    {
        $dto = new PayDto();
        $dto->userId = $this->arrayPost['userId'];
        $dto->sum = $this->arrayPost['sum'];

        $pay = $this->payService->create($dto);

        echo json_encode($pay);

    }

    public function confirmINTERKASSA()
    {
//        $id_kassa = "5ea0ac481ae1bd2f008b4568";
//        $secret_key = "tRZ3SbgDknx18lKw";

        $test_key = "cJLJLI3lM9JZeCyf";
        $dataSet = $this->arrayPost;

        unset($dataSet['ik_sign']);
        ksort($dataSet, SORT_STRING); // Sort elements in array by var names in alphabet queue
        array_push($dataSet, $test_key); // Adding secret key at the end of the string
        $signString = implode(':', $dataSet); // Concatenation calues using symbol ":"
        $sign = base64_encode(md5($signString, true)); // Get MD5 hash as binare view using generate string and code it in BASE64

        if($sign != $dataSet['ik_sign']){
            $response = [];
            $response['error'] = "Ошибка обработки платежа!";
            echo json_encode($response);
        }


        //        https://super-random.ru/#/spasibo     -   успех
        //        https://super-random.ru/#/pay_error   -   неудача
        //        $secret_key1 = 'w5o08o8f';   секрет1
        //        $secret_key2 = 'w5o08o8f';   секрет2
        //        Подтверждение платежа: - ответ сервера (YES)


    }


    public function confirmYANDEX()
    {


//        ЯНДЕКС ДЕНЬГИ
//        ["notification_type"]=>
//  string(13) "card-incoming"
//    ["operation_id"]=>
//  string(18) "640585388213018012"
//    ["amount"]=>
//  string(4) "9.80"
//    ["currency"]=>
//  string(3) "643"
//    ["datetime"]=>
//  string(20) "2020-04-19T04:23:08Z"
//    ["sender"]=>
//  string(0) ""
//    ["codepro"]=>
//  string(5) "false"
//    ["label"]=>
//  string(10) "1587266188"
//    ["withdraw_amount"]=>
//  string(5) "10.00"
//    ["unaccepted"]=>
//  string(5) "false"

//    ["sha1_hash"]=>
//  string(40) "b7a0e59dce0e51e4b82cec9599a7af8c6370e5cf"

        $ress = json_decode(file_get_contents("../tested444.php"), true);
        $secret_key = 'BJlsI0yYSUEDkkad5yDRY46B';


        $sha1 = sha1($ress['notification_type'] . '&' . $ress['operation_id'] . '&' . $ress['amount'] . '&' . $ress["currency"] . '&' . $ress['datetime'] . '&' . $ress['sender'] . '&' . $ress['codepro'] . '&' . $secret_key . '&' . $ress['label']);

        if ($sha1 != $ress['sha1_hash']) {
            // тут содержится код на случай, если верификация не пройдена
            exit();
        }
        // тут код на случай, если проверка прошла успешно
        $dto = new ConfirmDto();
        if (!empty($ress)) {
            $dto->amount = intval($ress["withdraw_amount"]);
            $dto->label = $ress["label"];
        } else {
            $dto->amount = intval($this->arrayPost["withdraw_amount"]);
            $dto->label = $this->arrayPost["label"];
        }
//        var_dump("Данные отправлены в сервис на обработку..."); echo "<br/>";
        $this->logger->flyLog("Данные отправлены в сервис на обработку...");
        $confirm = $this->payService->confirm($dto);
    }


    public function message()
    {
//        phpinfo();
        $postman = new Postman();
        $postman->setAdress("ztavruz1987@mail.ru");
        $postman->setSubject("Я ВАШУ МАМИНУ МАМУ ТРОГАЛ!");
        $postman->setMessage("цуйцуйцуйцуйцуйцуйцуйцуйцуйцуйцуйцуйцуйцу");
        $postman->sendMessage();
//
//        $postman->testMessage();
//        print mail("name@my.ru","header","text");
    }
}


