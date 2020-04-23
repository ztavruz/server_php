<?php


namespace App\Security\Token;


class JsonWebTokenTest
{
    /**
     * @var JsonWebTokenInterface
     */

    private  $jsonWebToken;

    /**
     * JsonWebTokenTest constructor.
     * @param JsonWebTokenInterface $jsonWebToken
     */
    public function __construct(JsonWebTokenInterface $jsonWebToken)
    {
        $this->jsonWebToken = $jsonWebToken;
    }

    private function partsCount(): bool
    {
        $token = $this->jsonWebToken->encode(['test' => 123], 10);

        $parts = explode(".", $token);

        return count($parts) == 3;
    }

    private function correctDecodeData(): bool
    {

        $rand = (string)mt_rand(0, 100);

        $token = $this->jsonWebToken->encode([
            'test' => 1234,
            'something' => 222,
            $rand => 'a'
        ]);

        $data = $this->jsonWebToken->decode($token);

        return isset($data['test']) &&
            isset($data['something']) &&
            isset($data['rand']) &&
            $data['test'] == 1234 &&
            $data['something'] == 222 &&
            $data[$rand] == 'a';
    }

    private function checkVerify(): bool
    {
        $token = $this->jsonWebToken->encode([]);

        return $this->jsonWebToken->verify($token);
    }

    private function checkExpired(): bool
    {
        $token = $this->jsonWebToken->encode([], 1);
        sleep(3);

        return $this->jsonWebToken->verify($token) == false;
    }

    private function checkFakedString(): bool
    {
        return $this->jsonWebToken->verify("1dwadiawdad.wa98da98wd.awiudaudw") == false;
    }

    private function passedOrThrow(string $message, string $callback): void
    {
        if(!call_user_func([$this, $callback])){
            throw new Exception($message);
        }
    }

    public function run(): void
    {
        try {
            $this->passedOrThrow('Parts count must be 3', 'partsCount');
            $this->passedOrThrow('Wrong data after decode', 'correctDecodeData');
            $this->passedOrThrow('Check verify', 'checkVerify');
            $this->passedOrThrow('Token expired wrong', 'checkExpired');
            $this->passedOrThrow('Faked passed', 'checkFakedString');

            echo "Tests passed successfully";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}