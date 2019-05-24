<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/24 0024
 * Time: 23:44
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'Token已过期或者无效';
    public $errorCode = 10001;
}