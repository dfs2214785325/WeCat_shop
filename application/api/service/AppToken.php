<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/7/14 0014
 * Time: 22:46
 */

namespace app\api\service;


use app\api\model\ThirdApp;
use app\lib\exception\TokenException;

class AppToken extends Token
{
    /**
     * 获取第三方用户的信息
     * @return string
     * @date  2019-7-14
     */
    public function get($ac, $se)
    {
        $app = ThirdApp::check($ac, $se);
        if (!$app) {
            throw new TokenException([
                'msg'       => '授权失败',
                'errorCode' => 10004
            ]);
        } else {
            $scope = $app->scope;
            $uid = $app->id;
            $values = [
                'scope' => $scope,
                'uid'   => $uid
            ];

            $token = $this->saveToCache($values);

            return $token;
        }
    }

    /**
     * 将需要数据存入缓存中
     * @param $values 需要存储的数据
     * @return $token:key(生成的token令牌)
     * @date  2019-5-24 21:18
     */
    private function saveToCache(array $values)
    {
        $token = self::generateToken();

        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $result = $redis->set($token, json_encode($values), 7200);

        if (!$result) {
            throw new TokenException([
                'msg' => '服务器缓存失败',
                'errorCode' => 10005,
            ]);
        }

        return $token;
    }
}