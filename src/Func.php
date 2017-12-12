<?php
/**
 * Created by PhpStorm.
 * User: cnbattle
 * Date: 17-11-18
 * Time: 下午3:20
 */

namespace PHPBase; 

class Func
{
    public static function info():string {
        return "This is a PHP commonly used function collection package.";
    }

    /**
     * 最好的随机数
     * @param int $min
     * @param int $max
     * @return int
     */
    public static function rand(int $min,int $max):int {
        return mt_rand($min,$max);
    }

    /**
     * 参数检测
     * @param $arr key数组
     * @param $parameter 检测的数组
     * @return bool
     */
    public static function parameterIsNull($arr,$parameter):bool {
        foreach ($arr as $key => $value){
            if (!array_key_exists($value,$parameter) && empty($parameter[$value])){
                die('缺乏必要参数:'.$value);
            }
        }
        return true;
    }


    /**
     * 唯一uuid方法
     * From:https://secure.php.net/manual/zh/function.uniqid.php
     */
    public static function uuid():string {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

                // 32 bits for "time_low"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),

                // 16 bits for "time_mid"
                mt_rand(0, 0xffff),

                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand(0, 0x0fff) | 0x4000,

                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand(0, 0x3fff) | 0x8000,

                // 48 bits for "node"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );
    }

    /**
     * @param $url
     * @param int $timeout
     * @param int $times
     * @return bool|string
     */
    public static function http_get($url, $timeout = 5, $times = 3) {
        $array = array(
            'http' => array(
                'method'=> 'GET',
                'timeout' => $timeout
            )
        );
        $stream = stream_context_create($array);
        while($times-- > 0) {
            $content = file_get_contents($url, NULL, $stream, 0, 4096000);
            if($content !== FALSE) return $content;
        }
        return FALSE;
    }

    /**
     * @param $url
     * @param string $post POST内容
     * @param string $cookie
     * @param int $timeout 超时时间
     * @param int $times 重试次数
     * @return bool|string
     */
    public static function http_post($url, $post = '', $cookie='', $timeout = 10, $times = 3) {
        is_array($post) AND $post = http_build_query($post);
        is_array($cookie) AND $cookie = http_build_query($cookie);
        $stream = stream_context_create(array('http' => array('header' => "Content-type: application/x-www-form-urlencoded\r\nx-requested-with: XMLHttpRequest\r\nCookie: $cookie\r\n", 'method' => 'POST', 'content' => $post, 'timeout' => $timeout)));
        while($times-- > 0) {
            $s = file_get_contents($url, NULL, $stream, 0, 4096000);
            if($s !== FALSE) return $s;
        }
        return FALSE;
    }

    /**
     * @param $url
     * @param string $cookie
     * @param int $timeout
     * @return mixed|string
     */
    public static function https_get($url, $cookie = '', $timeout=30) {
        return self::https_post($url, '', $cookie, $timeout);
    }

    /**
     * @param $url
     * @param string $post
     * @param string $cookie
     * @param int $timeout
     * @return mixed|string
     */
    public static function https_post($url, $post = '', $cookie = '', $timeout=30) {
        $w = stream_get_wrappers();
        $allow_url_fopen = strtolower(ini_get('allow_url_fopen'));
        $allow_url_fopen = (empty($allow_url_fopen) || $allow_url_fopen == 'off') ? 0 : 1;
        if(extension_loaded('openssl') && in_array('https', $w) && $allow_url_fopen) {
            return file_get_contents($url);
        } elseif (!function_exists('curl_init')) {
            return 'server not installed curl';
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 2);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在，默认可以省略
        if($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if($cookie) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: $cookie"));
        }
        (!ini_get('safe_mode') && !ini_get('open_basedir')) && curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转, 安全模式不允许
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $data = curl_exec($ch);
        if(curl_errno($ch)) {
            return 'Errno'.curl_error($ch);
        }
        if(!$data) {
            curl_close($ch);
            return '';
        }

        list($header, $data) = explode("\r\n\r\n", $data);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($http_code == 301 || $http_code == 302) {
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
     * 加密解密方法
     * @param $string: 明文 或 密文
     * @param string $operation: DECODE表示解密,其它表示加密
     * @param string $key: 密匙
     * @param int $expiry: 密文有效期
     * @return bool|string
     */
    public static function encrypt($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        $ckey_length = 4;
        // 密匙
        $key = md5($key != '' ? $key : $_SERVER['HTTP_HOST']);
        // 密匙a会参与加解密
        $keya = md5(substr($key, 0, 16));
        // 密匙b会用来做数据完整性验证
        $keyb = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
        // 参与运算的密匙
        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        // 产生密匙簿
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // 核心加解密部分
        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if($operation == 'DECODE') {
            // substr($result, 0, 10) == 0 验证数据有效性
            // substr($result, 0, 10) - time() > 0 验证数据有效性
            // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
            // 验证数据有效性，请看未加密明文的格式
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }

}