<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/7 0007
 * Time: 21:12
 */
namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code = 404;

    public $msg  = "请求banner不存在";

    public $errorCode = 40000;
}