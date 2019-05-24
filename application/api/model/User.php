<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/22
 * Time: 13:31
 */

namespace app\api\model;


class User extends BaseModel
{
    /**
     * 获取数据库中的相对应的openid用户信息
     * @param string $openid
     * @return array|null
     * @date  2019-5-24 21:57
     */
    public static function getByOpenID($openid)
    {
        $user = self::where('openid', $openid)->find();
        return $user;
    }

}