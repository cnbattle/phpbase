<?php
/**
 * Created by PhpStorm.
 * User: cnbattle
 * Date: 17-11-18
 * Time: 下午4:19
 */

namespace PHPBase;

use PHPBase\Error;

class Log
{
    protected $file_path = '';
    protected $data;

    public static function log($file_path,$data)
    {
        Error::output('asd');
    }
}