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
    private $key; // php -r "echo (openssl_random_pseudo_bytes(32));"
    private $iv; // php -r "echo (openssl_random_pseudo_bytes(16));"


    public function __construct($key, $iv) {
        $this->key = $key;
        $this->iv = $iv;
    }

    public function createKey() {
        $this->key = base64_encode(openssl_random_pseudo_bytes(32));
        return $this->key;
    }

    public function createIv() {
        $this->iv = base64_encode(openssl_random_pseudo_bytes(16));
        return $this->iv;
    }

    public function encrypt($data) {
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', base64_decode($this->key), OPENSSL_RAW_DATA, base64_decode($this->iv));
        return base64_encode($encrypted);

    }

    public function decrypt($data) {
        $decrypted = openssl_decrypt(base64_decode($data), 'aes-256-cbc', base64_decode($this->key), OPENSSL_RAW_DATA, base64_decode($this->iv));
        return $decrypted;
    }
}