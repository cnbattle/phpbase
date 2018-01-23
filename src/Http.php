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
     * @param string $cookie
     * @param int $timeout
     * @param int $times
     * @return bool|string
     */
    public static function httpGet($url, $cookie = '', $timeout = 30, $times = 3) {
        if (substr($url, 0, 8) == 'https://') {
            return self::httpsGet($url, $cookie, $timeout, $times);
        }
        $arr = array(
            'http' => array(
                'method' => 'GET',
                'timeout' => $timeout
            )
        );
        $stream = stream_context_create($arr);
        while ($times-- > 0) {
            $s = file_get_contents($url, NULL, $stream, 0, 4096000);
            if ($s !== FALSE) return $s;
        }
        return FALSE;
    }

    /**
     * @param $url
     * @param string $post
     * @param string $cookie
     * @param int $timeout
     * @param int $times
     * @return bool|string
     */
    public static function httpPost($url, $post = '', $cookie = '', $timeout = 30, $times = 3) {
        if (substr($url, 0, 8) == 'https://') {
            return self::httpsPost($url, $post, $cookie, $timeout, $times);
        }
        is_array($post) AND $post = http_build_query($post);
        is_array($cookie) AND $cookie = http_build_query($cookie);
        $stream = stream_context_create(array('http' => array('header' => "Content-type: application/x-www-form-urlencoded\r\nx-requested-with: XMLHttpRequest\r\nCookie: $cookie\r\n", 'method' => 'POST', 'content' => $post, 'timeout' => $timeout)));
        while ($times-- > 0) {
            $s = file_get_contents($url, NULL, $stream, 0, 4096000);
            if ($s !== FALSE) return $s;
        }
        return FALSE;
    }

    /**
     * @param $url
     * @param string $cookie
     * @param int $timeout
     * @param int $times
     * @return string
     */
    public static function httpsGet($url, $cookie = '', $timeout = 30, $times = 1) {
        if (substr($url, 0, 7) == 'http://') {
            return self::httpGet($url, $cookie, $timeout, $times);
        }
        return self::httpsPost($url, '', $cookie, $timeout, $times);
    }

    /**
     * @param $url
     * @param string $post
     * @param string $cookie
     * @param int $timeout
     * @param int $times
     * @return mixed|string
     */
    public static function httpsPost($url, $post = '', $cookie = '', $timeout = 30, $times = 1) {
        if (substr($url, 0, 7) == 'http://') {
            return self::httpPost($url, $post, $cookie, $timeout, $times);
        }
        is_array($post) AND $post = http_build_query($post);
        is_array($cookie) AND $cookie = http_build_query($cookie);
        $w = stream_get_wrappers();
        $allow_url_fopen = strtolower(ini_get('allow_url_fopen'));
        $allow_url_fopen = (empty($allow_url_fopen) || $allow_url_fopen == 'off') ? 0 : 1;
        if (extension_loaded('openssl') && in_array('https', $w) && $allow_url_fopen) {
            $stream = stream_context_create(array('http' => array('header' => "Content-type: application/x-www-form-urlencoded\r\nx-requested-with: XMLHttpRequest\r\nCookie: $cookie\r\n", 'method' => 'POST', 'content' => $post, 'timeout' => $timeout)));
            $s = file_get_contents($url, NULL, $stream, 0, 4096000);
            return $s;
        } elseif (!function_exists('curl_init')) {
            return Error::error(-1, 'server not installed curl.');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 2); // 1/2
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded', 'x-requested-with: XMLHttpRequest'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, _SERVER('HTTP_USER_AGENT'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在，默认可以省略
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        $header = array('Content-type: application/x-www-form-urlencoded', 'X-Requested-With: XMLHttpRequest');
        if ($cookie) {
            $header[] = "Cookie: $cookie";
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        (!ini_get('safe_mode') && !ini_get('open_basedir')) && curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转, 安全模式不允许
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            return Error::error(-1, 'Errno' . curl_error($ch));
        }
        if (!$data) {
            curl_close($ch);
            return '';
        }

        list($header, $data) = explode("\r\n\r\n", $data);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code == 301 || $http_code == 302) {
            $matches = array();
            preg_match('/Location:(.*?)\n/', $header, $matches);
            $url = trim(array_pop($matches));
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $data = curl_exec($ch);
        }
        curl_close($ch);
        return $data;
    }

    /**
     * 多线程抓取数据，需要CURL支持，一般在命令行下执行，此函数收集于互联网，由 xiuno 整理，经过测试，会导致 CPU 100%。
     * @param $urls
     * @return array
     */
    public static function httpMultiGet($urls) {
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