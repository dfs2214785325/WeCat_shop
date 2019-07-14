<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/22
 * Time: 13:21
 */
namespace app\api\controller\v1;

use app\api\service\AppToken;
use app\api\service\UserToken;
use app\api\validate\AppTokenGet;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;
use app\api\service\Token as TokenService;
use think\cache\driver\Redis;

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

    /**
     * 第三方应用获取令牌(类似于商户cms...)
     * @param $ac:access
     * @param static $se:secret
     * @date  2019-7-14
     */
    public function getAppToken($ac='' ,$se=''){
        //允许访问所有域
        header('Access-Control-Allow-Origin:*');
        //允许header头带的参数
        header("Access-Allow-Headers:token,Origin,x-Request-with,Content-Type,Accept")
        //允许访问类型
        header('Access-Control-Allow-Methods:POST,GET')

        (new AppTokenGet())->goCheck();
        $app = new AppToken();
        $token = $app->get($ac ,$se);

        return [
            'token' => $token
        ];
    }

}