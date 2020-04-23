<?php


namespace App\Helper;


class RandomGenerater
{

        public function generateKey() {

            $str = '01234567890qwertyuiopasdfghjklzxcvbnm';

            return substr(str_shuffle(str_repeat($str,30)),0,30);
        }

}