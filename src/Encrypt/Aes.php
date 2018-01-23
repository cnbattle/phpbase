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

    /**
     * 初始化生产 key iv,初次需要保存,以便后续解密
     * @return array
     */
    public function init() {
        $this->key = base64_encode(openssl_random_pseudo_bytes(32));
        $this->iv = base64_encode(openssl_random_pseudo_bytes(16));
        return ['key' => $this->key, 'iv' => $this->iv];
    }

    public function setKey($key) {
        $this->key = $key;
        return $this;
    }

    public function setIv($iv) {
        $this->iv = $iv;
        return $this;
    }

    /**
     * 加密
     * @param $data
     * @return string
     */
    public function encrypt($data) {
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', base64_decode($this->key), OPENSSL_RAW_DATA, base64_decode($this->iv));
        return base64_encode($encrypted);

    }

    /**
     * 解密
     * @param $data
     * @return string
     */
    public function decrypt($data) {
        $decrypted = openssl_decrypt(base64_decode($data), 'aes-256-cbc', base64_decode($this->key), OPENSSL_RAW_DATA, base64_decode($this->iv));
        return $decrypted;
    }
}