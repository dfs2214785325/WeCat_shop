<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/6/4 0004
 * Time: 20:25
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = 403;
    public $msg = "权限不够";
    public $errorCode = 10001;
}