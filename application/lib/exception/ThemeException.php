<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/13 0013
 * Time: 21:06
 */

namespace app\lib\exception;


class ThemeException extends BaseException
{
    public $code = 404;
    public $msg  = "指定的主题不存在";
    public $errorCode = 30000;

}