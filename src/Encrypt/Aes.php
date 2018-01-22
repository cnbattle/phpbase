<?php
/**
 * Created by PhpStorm.
 * User: cnbattle
 * Date: 18-1-22
 * Time: 下午12:04
 */

namespace PHPBase\Encrypt;

class Aes
{
    private $key;
    private $iv;
    public function __construct($key, $iv)
    {
        $this->key = $key;
        $this->iv = $iv;

    }
    public function encrypt($data)
    {
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', base64_decode($this->key), OPENSSL_RAW_DATA, base64_decode($this->iv));
        return base64_encode($encrypted);

    }
    public function decrypt($data)
    {
        $decrypted = openssl_decrypt(base64_decode($data), 'aes-256-cbc', base64_decode($this->key), OPENSSL_RAW_DATA, base64_decode($this->iv));
        return $decrypted;
    }
}