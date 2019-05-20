<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/9 0009
 * Time: 21:08
 */
namespace app\lib\exception;


class ParameterException extends BaseException
{
    // HTTP状态码
    public $code      = 400;
    //错误提示信息
    public $msg       = '参数错误';
    //错误码
    public $errorCode = 10000;



}