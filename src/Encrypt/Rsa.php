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
    private $privateKey = '';
    private $publicKey;

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

    public function rsaEncrypt($data) {
        openssl_public_encrypt($data, $crypted, $this->publicKey);
        return $crypted;
    }

    public function rsaDecrypt($data) {
        openssl_private_decrypt($data, $decrypted, $this->privateKey);
        return $decrypted;
    }
}