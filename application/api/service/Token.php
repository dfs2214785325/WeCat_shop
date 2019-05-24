<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/24 0024
 * Time: 22:31
 */

namespace app\api\service;


class Token
{
    /**
     * 生成Token令牌
     * @date  2019-5-24 22:33
     */
    public static function generateToken()
    {
        //32位字符串
        $rangChars = getRandChars(32);
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        //salt加密
        $salt = config('secret.token_salt');

        return md5($rangChars . $timestamp . $salt);
    }
}