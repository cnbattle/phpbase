<?php
/**
 * Created by PhpStorm.
 * User: cnbattle
 * Date: 18-6-15
 * Time: 上午11:22
 */

namespace PHPBaseFunc {
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

    /**
     * 批量搜索字符串
     * @param $str 搜索的字符串
     * @param $startChar 开始字符串
     * @param $endChar 结束字符串
     * @param bool $is_start 是否包含开始字符串,默认包含
     * @param bool $is_end 是否包含结束字符串,默认包含
     * @return array|string
     */
    function strSearchAll($str, $startChar, $endChar, $is_start = true, $is_end = true)
    {
        $startNum = 0;
        $endNum = 0;
        $arr = array();
        $startCount = substr_count($str, $startChar);// 获取开始字符串 出现的次数
        $endCount = substr_count($str, $endChar);// 获取结束字符串 出现的次数
        $startCharNum = strlen($startChar);
        $endCharNum = 0;

        if ($is_start) {
            $startCharNum = 0;
        }
        if ($is_end) {
            $endCharNum = strlen($endChar);
        }

        if ($startCount == $endCount) {
            for ($i = 0; $i < $startCount; $i++) {
                $startNum = strpos($str, $startChar, $startNum);
                $endNum = strpos($str, $endChar, $endNum);
                $tmp = substr($str, $startNum + $startCharNum, $endNum - $startNum + $endCharNum);
                $arr[] = $tmp;
                $startNum++;
                $endNum++;
            }
            return $arr;
        }
        return '数据不对称';
    }

    /**
     * 参数检测
     * @param $arr key数组
     * @param $parameter 检测的数组
     * @return bool
     */
    function parameterIsNull($arr, $parameter)
    {
        foreach ($arr as $key => $value) {
            if (!array_key_exists($value, $parameter) && empty($parameter[$value])) {
                die('缺乏必要参数:' . $value);
            }
        }
        return true;
    }

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

    /**
     * manyTimesAgo
     * @param $time
     * @param string $default_lang
     * @return string
     */
    function manyTimesAgo($time, $default_lang = 'zh_cn')
    {
        $timeDifference = time() - $time;
        $language['en'] = [' Year', ' Month', ' Week', ' Day', ' Hour', ' Minute', ' Second', ' Ago'];
        $language['zh_cn'] = ['年', '个月', '星期', '天', '小时', '分钟', '秒', '前'];
        $format = array(
            '31536000' => $language[$default_lang][0],
            '2592000' => $language[$default_lang][1],
            '604800' => $language[$default_lang][2],
            '86400' => $language[$default_lang][3],
            '3600' => $language[$default_lang][4],
            '60' => $language[$default_lang][5],
            '1' => $language[$default_lang][6]
        );
        foreach ($format as $key => $value) {
            if (0 != $num = floor($timeDifference / (int)$key)) {
                return $num . $value . $language[$default_lang][7];
            }
        }
    }
}