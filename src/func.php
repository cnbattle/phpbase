<?php
/**
 * Created by PhpStorm.
 * User: cnbattle
 * Date: 18-11-14
 * Time: 下午5:41
 */


if (!function_exists('parameterIsNull')) {
    /**
     * 参数检测
     * @param array $arr key数组
     * @param array $parameter 检测的数组
     * @return bool
     * @throws \Exception
     */
    function parameterIsNull(array $arr, array $parameter)
    {
        foreach ($arr as $key => $value) {
            if (!array_key_exists($value, $parameter) && empty($parameter[$value])) {
                throw new \Exception('缺乏必要参数:' . $value);
            }
        }
        return true;
    }
}

if (!function_exists('dd')) {
    /**
     * 打印输出
     * @return mixed
     */
    function dd()
    {
        header("Content-type: text/html; charset=utf-8");
        $args = func_get_args(); //获取参数
        if (count($args) < 1) {
            die('无参数');
        }
        echo '<div style="width:100%;text-align:left"><pre>';
        //循环输出
        foreach ($args as $arg) {
            if (is_array($arg)) {
                print_r($arg);
                echo '<hr>';
            } else if (is_string($arg)) {
                echo $arg . '<hr>';
            } else {
                var_dump($arg);
                echo '<hr>';
            }
        }
        echo '</pre></div>';
        die;
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