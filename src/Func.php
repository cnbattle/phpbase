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
    /**
     * 最好的随机数
     * @param int $min
     * @param int $max
     * @return int
     */
    public static function rand($min,$max) {
        return mt_rand($min,$max);
    }

    /**
     * 批量搜索字符串
     * @param $str 搜索的字符串
     * @param $startChar 开始字符串
     * @param $endChar 结束字符串
     * @return array|string
     */

    /**
     * 批量搜索字符串
     * @param $str 搜索的字符串
     * @param $startChar 开始字符串
     * @param $endChar 结束字符串
     * @param bool $is_start 是否包含开始字符串,默认包含
     * @param bool $is_end 是否包含结束字符串,默认包含
     * @return array|string
     */
    public static function strSearchAll($str, $startChar, $endChar, $is_start = true,$is_end = true){
        $startNum = 0;
        $endNum = 0;
        $arr = array();
        $startCount = substr_count($str, $startChar);// 获取开始字符串 出现的次数
        $endCount = substr_count($str, $endChar);// 获取结束字符串 出现的次数
        $startCharNum = strlen($startChar);
        $endCharNum = 0;

        if ($is_start) { $startCharNum = 0;}
        if ($is_end) { $endCharNum = strlen($endChar);}

        if ($startCount == $endCount) {
            for($i = 0; $i < $startCount; $i++){
                $startNum = strpos($str, $startChar, $startNum);
                $endNum = strpos($str, $endChar, $endNum);
                $tmp = substr($str,$startNum+$startCharNum,$endNum-$startNum+$endCharNum);
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
    public static function parameterIsNull($arr,$parameter) {
        foreach ($arr as $key => $value){
            if (!array_key_exists($value,$parameter) && empty($parameter[$value])){
                die('缺乏必要参数:'.$value);
            }
        }
        return true;
    }


}