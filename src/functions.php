<?php
/**
 * Created by PhpStorm.
 * User: cnbattle
 * Date: 18-11-14
 * Time: 下午5:41
 */

// 无 Notice 方式的获取超级全局变量中的 key

if (!function_exists('_GET')) {
    function _GET($k, $def = NULL)
    {
        return isset($_GET[$k]) ? $_GET[$k] : $def;
    }
}

if (!function_exists('_POST')) {
    function _POST($k, $def = NULL)
    {
        return isset($_POST[$k]) ? $_POST[$k] : $def;
    }
}

if (!function_exists('_COOKIE')) {
    function _COOKIE($k, $def = NULL)
    {
        return isset($_COOKIE[$k]) ? $_COOKIE[$k] : $def;
    }
}


if (!function_exists('_REQUEST')) {
    function _REQUEST($k, $def = NULL)
    {
        return isset($_REQUEST[$k]) ? $_REQUEST[$k] : $def;
    }
}

if (!function_exists('_ENV')) {
    function _ENV($k, $def = NULL)
    {
        return isset($_ENV[$k]) ? $_ENV[$k] : $def;
    }
}
if (!function_exists('_SERVER')) {
    function _SERVER($k, $def = NULL)
    {
        return isset($_SERVER[$k]) ? $_SERVER[$k] : $def;
    }
}

if (!function_exists('GLOBALS')) {
    function GLOBALS($k, $def = NULL)
    {
        return isset($GLOBALS[$k]) ? $GLOBALS[$k] : $def;
    }
}
if (!function_exists('G')) {
    function G($k, $def = NULL)
    {
        return isset($GLOBALS[$k]) ? $GLOBALS[$k] : $def;
    }
}

if (!function_exists('_SESSION')) {
    function _SESSION($k, $def = NULL)
    {
        return isset($_SESSION[$k]) ? $_SESSION[$k] : $def;
    }
}

if (!function_exists('makeDirs')) {
    /**
     * 递归创建目录
     * @param $dir
     * @param int $mode
     * @return bool
     */
    function makeDirs($dir, $mode = 0777)
    {
        return is_dir($dir) or makeDirs(dirname($dir)) and mkdir($dir, $mode);
    }
}

if (!function_exists('rand')) {
    /**
     * 最好的随机数
     * @param int $min
     * @param int $max
     * @return int
     */
    function rand($min, $max)
    {
        return mt_rand($min, $max);
    }
}

if (!function_exists('getClientIP')) {
    /**
     * 获取访问者ip
     * @return array|false|string
     */
    function getClientIP()
    {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}

if (!function_exists('manyTimesAgo')) {
    /**
     * manyTimesAgo
     * @param $time
     * @param string $lang
     * @return string
     */
    function manyTimesAgo($time, $lang = 'zh_cn')
    {
        $timeDifference = time() - $time;
        $language['en'] = [' Year', ' Month', ' Week', ' Day', ' Hour', ' Minute', ' Second', ' Ago'];
        $language['zh_cn'] = ['年', '个月', '星期', '天', '小时', '分钟', '秒', '前'];
        $format = array(
            '31536000' => $language[$lang][0],
            '2592000' => $language[$lang][1],
            '604800' => $language[$lang][2],
            '86400' => $language[$lang][3],
            '3600' => $language[$lang][4],
            '60' => $language[$lang][5],
            '1' => $language[$lang][6]
        );
        foreach ($format as $key => $value) {
            if (0 != $num = floor($timeDifference / (int)$key)) {
                return $num . $value . $language[$lang][7];
            }
        }
        return '';
    }
}

