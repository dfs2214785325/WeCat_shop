<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/18 0018
 * Time: 22:04
 */

namespace app\lib\exception;


class ProductException extends BaseException
{
    public $code      = 404;
    public $msg       = "指定商品不存在";
    public $errorCode = 20000;
}