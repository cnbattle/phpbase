<?php
/**
 * Created by PhpStorm.
 * User: cnbattle
 * Date: 18-2-28
 * Time: 上午11:10
 */

namespace PHPBase\Http;

class Post
{
    /**
     * @param $url
     * @param bool $post
     * @param bool $header
     * @param int $timeout
     * @return mixed|string
     */
    public static function Url($url, $post = false, $header = false, $timeout = 30) {
        if (substr($url, 0, 8) == 'https://') {
            return Func::httpsPost($url, $post, $timeout);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        if ($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        $error = curl_error($curl);
        return $error ? $error : $result;
    }
}