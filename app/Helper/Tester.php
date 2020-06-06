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

    public function question16()
    {
        $data = [
            "books" => [
                [
                    "title_book" => "Землянин",
                    "year_of_manufacture" => 2012,
                    "author_id" => 3
                ],
                [
                    "title_book" => "Шаг к звездам",
                    "year_of_manufacture" => 2012,
                    "author_id" => 3
                ],
                [
                    "title_book" => "На службе Великого дома",
                    "year_of_manufacture" => 2012,
                    "author_id" => 3
                ],
                [
                    "title_book" => "Русские не сдаются!",
                    "year_of_manufacture" => 2012,
                    "author_id" => 3
                ],
                [
                    "title_book" => "Рекрут",
                    "year_of_manufacture" => 2019,
                    "author_id" => 2
                ],
                [
                    "title_book" => "Наемник",
                    "year_of_manufacture" => 2019,
                    "author_id" => 2
                ],
                [
                    "title_book" => "Офицер",
                    "year_of_manufacture" => 2019,
                    "author_id" => 2
                ],
                [
                    "title_book" => "Ангел-Хранитель 320",
                    "year_of_manufacture" => 2005,
                    "author_id" => 1
                ],
            ],

            "authors" => [
                "1" => [
                    "id" => 3,
                    "name" => "Игорь Поль",
                    "email" => "igor@qwerty.ru",
                    "year_of_birth" => 1967
                ],
                "2" => [
                    "id" => 2,
                    "name" => "Алекс Каменев",
                    "email" => "alex@qwerty.ru",
                    "year_of_birth" => 1978
                ],
                "3" => [
                    "name" => "Роман Злотников",
                    "email" => "roman@qwerty.ru",
                    "year_of_birth" => 1965
                ],

            ],
        ];

        echo "===Книги===" . "<br/>";
        foreach ($data["books"] as $book) {
            echo $book["title_book"] . " - " . $data["authors"][$book["author_id"]]["name"] . " - " . $book["year_of_manufacture"] . "<br/>";
        }
        echo "<br/>";
        echo "===Авторы===" . "<br/>";
        foreach ($data["authors"] as $author) {
            echo $author["name"] . " - " . $author["email"] . " - " . $author["year_of_birth"] . "<br/>";
        }
    }

    public function task17()
    {
//        echo "Количество итераций заданно числом \$n равное " . $n . "." . "<br/>";
        echo "Количество итераций заданно числом \$n равное " . 108 . "." . "<br/>";
        echo "<br/>";
        $sum = 0;

        for ($i = 0; $i <= 108; $i++) {

            echo "текущее число = " . $i . "<br/>" ;

            if ($i % 3 == 0 && $i % 5 != 0) {
                echo "<br/>";
                echo "...далее: " . "<br/>";
                echo "так как текущее число сейчас = " . $i . ", и оно соотвествует условию (\$n % 3 == 0 )" . "<br/>";
                echo "просто показываю текущее значение \$n = " . $i;
                echo "<br/>";
                echo "<br/>";

            } elseif ($i % 3 != 0 && $i % 5 == 0) {
                echo "<br/>";
                echo "...далее: " . "<br/>";
                echo "так как текущее число сейчас = " . $i . ", и оно соотвествует условию (\$n % 5 == 0)" . "<br/>" . "тогда суммирую \$n(".$i.") + \$sum(".$sum.")" . "<br/>";
                $sum = $sum + $i;
                echo "в этоге \$sum = " . $sum;
                echo "<br/>";
                echo "<br/>";

            } elseif ($i % 3 == 0 && $i % 5 == 0) {
                echo "<br/>";
                echo "...далее: " . "<br/>";
                echo "так как текущее число сейчас = " . $i . ", и оно соотвествует условию (\$n % 3 == 0 && \$n % 5 == 0)" . "<br/>" . "тогда вычитаю 1 из \$sum(".$sum.")" . "<br/>";
                $sum -=1;
                echo "в этоге \$sum = " . $sum;
                echo "<br/>";
                echo "<br/>";
            }
        }
        echo "(total sum) = " . $sum;
    }


}