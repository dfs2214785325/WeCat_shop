<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/23 0023
 * Time: 21:56
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
    public $code = 400;
    public $msg = "微信服务器接口调用失败";
    public $errorCode = 999;
}