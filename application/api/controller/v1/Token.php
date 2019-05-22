<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/22
 * Time: 13:21
 */
namespace app\api\controller\v1;

use app\api\service\UserToken;
use app\api\validate\TokenGet;

class Token
{

    /**
     * 通过用户的code值来查询它的Token
     * @param string $code  code值
     * @date 2019-5-22 13:30
     */
    public function getToken($code = '')
    {
        (new TokenGet())->goCheck();

        //实例化service层的类
        $userToken = new UserToken();
        $token = $userToken->get($code);

        return $token;

    }
}