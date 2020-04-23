<?php


namespace App\Security\Token;

use App\Security\Token\JsonWebTokenInterface;

class Token implements JsonWebTokenInterface
{
    private $header  = [];
    private $secret;

    public function __construct()
    {

        $this->header = [
            "alg"   => "sha256",
            "typ"   => "JWT",
            "end"   => 10
        ];

        $this->secret = 'veron';

    }
    //////////////////////////////////////////////////////////////////

    public function encode(array $data, int $lifeSeconds = 10): string
    {
        $header = $this->header;
        $header["end"] = $this->setEndToken($lifeSeconds);
        $algoritm = $header['alg'];
        $secret = $this->secret;

        $encodeData = [];

        $header = json_encode($header);
        $header = base64_encode($header);

        $payload = json_encode($data);
        $payload = base64_encode($payload);

        $signature = $header . "." . $payload;
        $signature = hash_hmac($algoritm, $signature, $secret);
        $signature = base64_encode($signature);

        $token = $header . "." . $payload . "." . $signature;

        return $token;
    }

    public function decode(string $token): array
    {
        $arr = explode(".", $token);
        $header    = $arr[0];
        $payload   = $arr[1];
        $signature = $arr[2];

        $payload = base64_decode($payload);
        $payload = json_decode($payload,true);

        return $payload;
    }

    public function verify(string $token): bool
    {
        $token = $token;
        $arr = explode(".", $token);
        $header    = $arr[0];
        $payload   = $arr[1];
        $signature = $arr[2];


        $algoritm = json_decode(base64_decode($header),true);
        if ($algoritm["alg"] != "sha256") {
            $algoritm["alg"] = "sha256";
        }

        $secret = $this->secret;


        $signature2 = $header . "." . $payload;

        $signature2 = hash_hmac($algoritm['alg'], $signature2, $this->secret);

        $signature2 = base64_encode($signature2);

        $end = base64_decode($header);
        $end = json_decode($end,true);
        $end = $end['end'];

//        var_dump($payload);
//        var_dump($signature == $signature2);
//        var_dump($end > time());

        return isset($payload) && $signature == $signature2 && $end > time();
    }

    /////////////////////////////////////////////////////////////////////////////

    private function setEndToken($seconds)
    {
        $start  = time();
        $result = $start + $seconds;
        return $result;
    }

    private function getDays($kolvo)
    {
        $result = 1*60*60*24*$kolvo;
        return $result;
    }
}