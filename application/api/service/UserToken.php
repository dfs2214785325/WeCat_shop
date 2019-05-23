<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/22
 * Time: 13:32
 */

namespace app\api\service;

use app\lib\exception\WeChatException;
use think\Exception;

class UserToken
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
                $this->processLoginError($wxResult);
            } else {
                //成功
                $this->grantToken($wxResult);
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
        //取openid，再去查数据库中是否存在。假如不存在，则新增一条记录。
        //生成令牌，存入缓存中，在将令牌返回到客户端中
        $openid = $wxResult['openid'];
        return $openid;
    }
}