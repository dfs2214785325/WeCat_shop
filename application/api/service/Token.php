<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/24 0024
 * Time: 22:31
 */

namespace app\api\service;

use app\lib\exception\TokenException;
use think\Exception;
use think\facade\Request;

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

    /**
     * 获取微信请求后，需要的键值参数
     * @param string $key 参数键值
     * @date  2019-5-31
     */
    public static function getCurrentTokenVar($key)
    {
        $token = Request::instance()->header('token');

        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);

        $vars = $redis->get($token);
        if (!$vars) {
            throw new TokenException();
        } else {
            // 判断是否是数组字符串（redis--）
            if (!is_array($vars)) {
                $vars = json_decode($vars, true);
            }
            if (array_key_exists($key, $vars)) {
                // 假如存在需要的值，就返回回去
                return $vars[$key];
            } else {
                throw new Exception('尝试获取的Token变量不存在');
            }
        }
    }

    /**
     * 获取用户uid
     * @date  2019-5-31
     */
    public static function getCurrentUid()
    {
        // token
        $uid = self::getCurrentTokenVar('uid');

        return $uid;
    }

}