<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/6/8 0008
 * Time: 1:52
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
    public $code = 404;
    public $msg = "订单不存在，请检查ID";
    public $errorCode = 80000;
}