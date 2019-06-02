<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/6/2 0002
 * Time: 10:25
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 404;
    public $msg = "用户不存在";
    public $errorCode = 60000;
}