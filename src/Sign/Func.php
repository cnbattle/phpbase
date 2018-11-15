<?php
/**
 * Created by PhpStorm.
 * User: cnbattle
 * Date: 18-2-28
 * Time: 上午11:26
 */

namespace PHPBase\Sign;

class Func
{
    /**
     * 获取安全验证sign
     * @param $postData
     * @param string $signKey
     * @return array
     */
    public function getVerifySign($postData, $signKey = 'cnbattle')
    {
        ksort($postData);
        $str = http_build_query($postData);
        $timestamp = time();
        $sign = md5($signKey . $str . $timestamp);
        return ['sign' => $sign, 'timestamp' => $timestamp];
    }
}