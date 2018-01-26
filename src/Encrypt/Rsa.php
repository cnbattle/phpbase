<?php
/**
 * Created by PhpStorm.
 * User: cnbattle
 * Date: 18-1-22
 * Time: 上午11:49
 */

namespace PHPBase\Encrypt;


class Rsa
{
    // 公钥和私钥
    private $privateKey;
    private $publicKey;

    public function __construct() {
        return $this;
    }
    /**
     * @param string $privateKey
     */
    public function setPrivateKey($privateKey) {
        $this->privateKey = $privateKey;
        return $this;
    }

    /**
     * @param string $publicKey
     */
    public function setPublicKey($publicKey) {
        $this->publicKey = $publicKey;
        return $this;
    }

    /**
     * @param $data
     * @return string
     */
    public function encrypt($data) {
        openssl_public_encrypt($data, $crypted, $this->publicKey);
        return base64_encode($crypted);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function decrypt($data) {
        openssl_private_decrypt(base64_decode($data), $decrypted, $this->privateKey);
        return $decrypted;
    }
}