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
use app\lib\exception\ParameterException;
use app\api\service\Token as TokenService;

class Token
{

    /**
     * 通过用户的code值来查询它的Token
     * @param string $code code值
     * @date 2019-5-22 13:30
     */
    public function getToken($code = '')
    {
        (new TokenGet())->goCheck();

        //实例化service层的类
        $userToken = new UserToken($code);
        $token = $userToken->get();

        return json($token);
    }

    /**
     * 验证token是否有效合法
     * @param string $token
     * @date  20149-7-14
     */
    public function verifyToken($token = '')
    {
        if(!$token){
            throw new ParameterException(['token不能为空']);
        }
        $valid = TokenService::verifyToken($token);

        return [
            'isValid' => $valid
        ];
    }
}