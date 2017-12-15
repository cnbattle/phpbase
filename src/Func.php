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


}