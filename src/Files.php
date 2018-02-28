<?php
/**
 * Created by PhpStorm.
 * User: cnbattle
 * Date: 18-2-28
 * Time: 上午10:54
 */

namespace PHPBase;

class Files
{
    /**
     * 递归创建目录
     * @param $dir
     * @param int $mode
     * @return bool
     */
    public function makeDirs($dir, $mode = 0777) {
        return is_dir($dir) or $this->makeDirs(dirname($dir)) and mkdir($dir, $mode);
    }
}