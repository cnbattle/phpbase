<?php
/**
 * Created by PhpStorm.
 * User: cnbattle
 * Date: 18-11-15
 * Time: 上午8:55
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