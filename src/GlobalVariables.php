<?php
/**
 * Created by PhpStorm.
 * User: cnbattle
 * Date: 18-1-26
 * Time: 下午3:15
 * description: 无 Notice 方式的获取超级全局变量中的 key
 */

namespace PHPBase;


class GlobalVariables
{
    /**
     * @param $key
     * @param null $def
     * @return null
     */
    public static function GET($key, $def = NULL) {
        return isset($_GET[$key]) ? $_GET[$key] : $def;
    }

    /**
     * @param $key
     * @param null $def
     * @return null
     */
    public static function POST($key, $def = NULL) {
        return isset($_POST[$key]) ? $_POST[$key] : $def;
    }

    /**
     * @param $key
     * @param null $def
     * @return null
     */
    public static function COOKIE($key, $def = NULL) {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : $def;
    }

    /**
     * @param $key
     * @param null $def
     * @return null
     */
    public static function REQUEST($key, $def = NULL) {
        return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $def;
    }

    /**
     * @param $key
     * @param null $def
     * @return null
     */
    public static function ENV($key, $def = NULL) {
        return isset($_ENV[$key]) ? $_ENV[$key] : $def;
    }

    /**
     * @param $key
     * @param null $def
     * @return null
     */
    public static function SERVER($key, $def = NULL) {
        return isset($_SERVER[$key]) ? $_SERVER[$key] : $def;
    }

    /**
     * @param $key
     * @param null $def
     * @return mixed|null
     */
    public static function GLOBALS($key, $def = NULL) {
        return isset($GLOBALS[$key]) ? $GLOBALS[$key] : $def;
    }

    /**
     * @param $key
     * @param null $def
     * @return mixed|null
     */
    public static function G($key, $def = NULL) {
        return isset($GLOBALS[$key]) ? $GLOBALS[$key] : $def;
    }

    /**
     * @param $key
     * @param null $def
     * @return null
     */
    public static function SESSION($key, $def = NULL) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $def;
    }
}