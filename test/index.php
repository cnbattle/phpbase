<?php
/**
 * Created by PhpStorm.
 * User: cnbattle 启爱
 * Date: 2017/12/12 - 21:20
 * Description:
 */
use PHPBase\Func;

$str = 'abc';
$key = 'www.cnbattle.com';
$token = Func::encrypt($str, 'E', $key);
echo '加密:'.Func::encrypt($str, 'E', $key);
echo '解密：'.Func::encrypt($str, 'D', $key);

