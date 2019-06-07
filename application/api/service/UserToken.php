<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/22
 * Time: 13:32
 */
namespace app\api\service;

use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;

class UserToken extends Token
{
    //客户端code值
    protected $code;
    //小程序AppID
    protected $wxAppID;
    //小程序AppSecret
    protected $wxAppSecret;
    //小程序登录请求地址
    protected $wxLoginUrl;

    public function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');

        //拼接url地址
        $this->wxLoginUrl = sprintf(config('wx.login_url'), $this->wxAppID, $this->wxAppSecret, $this->code);
    }


    /**
     * 发送小程序登录请求
     * @url  /token/user?code=''
     * @throws Exception
     * @throws WeChatException
     * @date  2019-5-22 21:54
     */
    public function get()
    {
        $result = curl_get($this->wxLoginUrl);

        //把字符串转换成数组
        $wxResult = json_decode($result, true);

        //校验是否通过
        if (empty($wxResult)) {
            throw new Exception('获取session_key及openid异常，微信内部错误');
        } else {
            //假如失败，微信会返回errcode码（具体看文档）
            $loginFail = array_key_exists('errcode', $wxResult);
            if ($loginFail) {
                //失败则抛出异常
                return $this->processLoginError($wxResult);
            } else {
                //成功
                return $this->grantToken($wxResult);
            }
        }
    }

    /**
     * 获取微信服务器登录异常信息
     * @param array $wxResult 微信服务器返回值
     * @date  2019-5-23 21:55
     */
    private function processLoginError(array $wxResult)
    {
        //返回微信服务器那边的异常值
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }

    /**
     * 验证并获取token令牌
     * @param array
     * @date  2019-5-23 22:02
     */
    private function grantToken(array $wxResult)
    {
        //取openid，再去查数据库中是否存在。
        $openid = $wxResult['openid'];
        $users = UserModel::getByOpenID($openid);

        if ($users) {
            //假如存在，取出用户数据
            $uid = $users->id;
        } else {
            //假如不存在，则新增一条记录。
            $uid = $this->newUseradd($openid);
        }

        //生成令牌，存入缓存中，在将令牌返回到客户端中，key:令牌 value:wxResult,uid,scope(用户身份)
        $cachedValue = $this->prepareCachedValue($wxResult, $uid);
        $token = $this->saveToCache($cachedValue);

        return $token;

    }

    /**
     * 写入新的openid数据
     * @param string $openid
     * @date  2019-5-24
     */
    private function newUseradd(string $openid): int
    {
        $user = UserModel::create([
            'openid' => $openid,
        ]);

        return $user->id;
    }

    /**
     * 存储微信返回值及用户id、权限
     * @param array $wxResult 微信返回值
     * @param int $uid 用户ID
     * @date  2019-5-24
     */
    private function prepareCachedValue(array $wxResult, int $uid)
    {
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        //权限值,普通用户权限
        $cachedValue['scope'] = ScopeEnum::User;

        return $cachedValue;
    }

    /**
     * 将需要数据存入缓存中
     * @date  2019-5-24 21:18
     */
    private function saveToCache(array $cachedValue): string
    {
        $key = self::generateToken();

        //数组转为字符串
        $value = json_encode($cachedValue);

        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $result = $redis->set($key, $value, 7200);

        if (!$result) {
            throw new TokenException([
                'msg' => '服务器缓存失败',
                'errorCode' => 10005,
            ]);
        }

        return $key;
    }
}
