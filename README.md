#### PHPBase
This is a PHP commonly used function collection package.

这是一个PHP常用函数集合包。

composer installer

`composer require cnbattle/phpbase`

Demo:
````
<?php
/**
 * Created by PhpStorm.
 * User: cnbattle
 * Date: 17-12-11
 * Time: 下午3:22
 */
use PHPBase\Func;

$uuid = Func::uuid();
$num = Func::rand(1,1000);

$http_url = 'http://www.cnbattle.com';
$https_url = 'https://www.cnbattle.com';
$post = ['name'=>'cnbattle','address'=>'kunming']; // post数组
$cookie = ['token'=>md5('cnbattle')]; // cookie数组
$timeout = 5; // 超时时间
$times = 3; // 重试次数

Func::http_get($http_url,$timeout,$times);
Func::http_post($http_url,$post,$cookie,$timeout,$times);
Func::https_get($https_url,$cookie,$timeout);
Func::https_post($https_url,$post,$cookie,$timeout);
````

Optimization suggestions [issues](https://github.com/cnbattle/phpbase/issues),please

优化建议请[issues](https://github.com/cnbattle/phpbase/issues)