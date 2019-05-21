<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/21 0021
 * Time: 20:23
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
    public $code      = 404;
    public $msg       = "指定分类不存在，请检查参数";
    public $errorCode = 50000;
}