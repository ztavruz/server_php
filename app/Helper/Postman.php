<?php


namespace App\Helper;


class Postman
{
    private $adress;
    private $subject;
    private $message;


    public function sendMessage()
    {
        $to = $this->adress;

        $subject = $this->subject;

        $message = $this->message;

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'From: ztavruz1987@yandex.ru' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf8' . "\r\n";

        mail($to, $subject, $message, $headers);

    }

    public function testMessage()
    {
        // несколько получателей
        $to = 'ztavruz1987@mail.ru'; // обратите внимание на запятую

// тема письма
        $subject = 'Тестовое2';

// текст письма
        $message = '
<html>
<head>
  <title>Birthday Reminders for August</title>
</head>
<body>
  <p>Here are the birthdays upcoming in August!</p>
  <table>
    <tr>
      <th>Person</th><th>Day</th><th>Month</th><th>Year</th>
    </tr>
    <tr>
      <td>Johny</td><td>10th</td><td>August</td><td>1970</td>
    </tr>
    <tr>
      <td>Sally</td><td>17th</td><td>August</td><td>1973</td>
    </tr>
  </table>
</body>
</html>
';

// Для отправки HTML-письма должен быть установлен заголовок Content-type
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'From: webmaster@example.com' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf8' . "\r\n";


// Отправляем
        mail($to, $subject, $message,  $headers);
    }


    /**
     * @param mixed $adress
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;
    }


    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }


    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }


}