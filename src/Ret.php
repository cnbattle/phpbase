<?php
/**
 * Created by PhpStorm.
 * User: cnbattle
 * Date: 18-8-31
 * Time: 下午5:11
 */

namespace PHPBase;


class Ret
{
    /**
     * 输出一维kv数组内容
     * @param int $status
     * @param string $message
     * @param array $data
     * @return false|string
     */
    public static function object(int $status, string $message, array $data = []) {
        return json_encode([
            'status' => $status,
            'message' => $message,
            'data' => (object)$data
        ]);
    }

    /**
     * 输出列表内容
     * @param int $status
     * @param string $message
     * @param array $data
     * @param array $other
     * @return false|string
     */
    public static function list(int $status, string $message, array $data = []) {
        return json_encode([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
    }
}