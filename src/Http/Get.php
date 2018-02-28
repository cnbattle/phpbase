<?php
/**
 * Created by PhpStorm.
 * User: cnbattle
 * Date: 18-2-28
 * Time: 上午11:10
 */

namespace PHPBase\Http;

class Get
{
    /**
     * @param $url
     * @param int $timeout
     * @return bool|string
     */
    public static function Url($url, $timeout = 30) {
        if (substr($url, 0, 8) == 'https://') {
            return Func::httpsGet($url, $timeout);
        }
        return Func::httpPost($url, false, false, $timeout);
    }
}