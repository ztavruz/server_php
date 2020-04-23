<?php


namespace App\Security\Token;

/**
 * Interface JsonWebTokenInterface
 */
interface JsonWebTokenInterface
{
    /**
     * @param string $token
     * @return bool
     */
    public function verify(string $token): bool;

    /**
     * @param array $data
     * @param int $lifeSeconds
     * @return string
     */
    public function encode(array $data, int $lifeSeconds = 10): string;

    /**
     * @param string $token
     * @return array
     */
    public function decode(string $token): array;
}