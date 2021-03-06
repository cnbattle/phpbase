<?php
/**
 * Created by PhpStorm.
 * User: cnbattle
 * Date: 17-12-15
 * Time: 上午11:56
 */

namespace PHPBase;


class Http
{
    /**
     * @param $url
     * @param array $query
     * @param array $header
     * @param int $timeout
     * @return bool|mixed|string
     */
    public static function httpGet(string $url, array $query = [], array $header = [], int $timeout = 30)
    {
        $build_query = http_build_query($query);
        if ($build_query != '') {
            $url = $url . '?' . $build_query;
        }
        if (substr($url, 0, 8) == 'https://') {
            return self::httpsGet($url, $query, $header, $timeout);
        }
        return self::httpPost($url, [], $query, $header, $timeout);
    }

    /**
     * @param $url
     * @param array $post
     * @param array $query
     * @param array $header
     * @param int $timeout
     * @return mixed|string
     */
    public static function httpPost(string $url, array $post = [], array $query = [], array $header = [], int $timeout = 30)
    {
        $build_query = http_build_query($query);
        if ($build_query != '') {
            $url = $url . '?' . $build_query;
        }
        if (substr($url, 0, 8) == 'https://') {
            return self::httpsPost($url, $post, $query, $header, $timeout);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        if ($header) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        if ($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        $error = curl_error($curl);
        return $error ? $error : $result;
    }

    /**
     * @param $url
     * @param array $query
     * @param array $header
     * @param int $timeout
     * @return bool|mixed|string
     */
    public static function httpsGet(string $url, array $query = [], array $header = [], int $timeout = 30)
    {
        if (substr($url, 0, 7) == 'http://') {
            return self::httpGet($url, $timeout);
        }
        return self::httpsPost($url, [], $query, $header, $timeout);
    }

    /**
     * @param $url
     * @param array $post
     * @param array $query
     * @param array $header
     * @param int $timeout
     * @return mixed|string
     */
    public static function httpsPost(string $url, array $post = [], array $query = [], array $header = [], int $timeout = 30)
    {
        if (substr($url, 0, 7) == 'http://') {
            return self::httpPost($url, $post, $header, $timeout);
        }
        $build_query = http_build_query($query);
        if ($build_query != '') {
            $url = $url . '?' . $build_query;
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        if ($header) {
            $headers = [];
            foreach ($header as $key => $value) {
                $headers[] = $key . ':' . $value;
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        if ($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($curl);
        $error = curl_error($curl);
        return $error ? $error : $result;
    }

    /**
     * Post 发送json字符串
     * @param $url
     * @param $jsonStr
     * @param array $query
     * @param array $header
     * @param int $timeout
     * @return mixed|string
     */
    public function httpPostJson(string $url, string $jsonStr, array $query = [], array $header = [], int $timeout = 30)
    {
        $build_query = http_build_query($query);
        if ($build_query != '') {
            $url = $url . '?' . $build_query;
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        if ($header) {
            $headers = [];
            foreach ($header as $key => $value) {
                $headers[] = $key . ':' . $value;
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonStr);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($jsonStr)
            )
        );
        $result = curl_exec($curl);
        $error = curl_error($curl);
        return $error ? $error : $result;
    }

    /**
     * 多线程抓取数据，需要CURL支持，一般在命令行下执行，此函数收集于互联网，由 xiuno 整理，经过测试，会导致 CPU 100%。
     * @param $urls
     * @return array
     */
    public static function httpMultiGet(array $urls)
    {
        // 如果不支持，则转为单线程顺序抓取
        $data = array();
        if (!function_exists('curl_multi_init')) {
            foreach ($urls as $k => $url) {
                $data[$k] = self::httpsGet($url);
            }
            return $data;
        }

        $multi_handle = curl_multi_init();
        foreach ($urls as $i => $url) {
            $conn[$i] = curl_init($url);
            curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER, 1);
            $timeout = 3;
            curl_setopt($conn[$i], CURLOPT_CONNECTTIMEOUT, $timeout); // 超时 seconds
            curl_setopt($conn[$i], CURLOPT_FOLLOWLOCATION, 1);
            //curl_easy_setopt(curl, CURLOPT_NOSIGNAL, 1);
            curl_multi_add_handle($multi_handle, $conn[$i]);
        }
        do {
            $mrc = curl_multi_exec($multi_handle, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active and $mrc == CURLM_OK) {
            if (curl_multi_select($multi_handle) != -1) {
                do {
                    $mrc = curl_multi_exec($multi_handle, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
        foreach ($urls as $i => $url) {
            $data[$i] = curl_multi_getcontent($conn[$i]);
            curl_multi_remove_handle($multi_handle, $conn[$i]);
            curl_close($conn[$i]);
        }
        return $data;
    }
}