<?php
/**
 * Created by PhpStorm.
 * User: cnbattle 启爱
 * Date: 2017/12/12 - 21:20
 * Description:
 */
use PHPBase\Func;

$str = '2017-12-23-123';
$key = 'www.cnbattle.com';

$str = 'abcdef';
$key = 'www.cnbattle.com';
$authcode =  Func::encrypt($str,'ENCODE',$key,0); //加密
echo $authcode;
echo Func::encrypt($authcode,'DECODE',$key,0); //解密


