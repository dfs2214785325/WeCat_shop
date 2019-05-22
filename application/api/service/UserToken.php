<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/22
 * Time: 13:32
 */

namespace app\api\service;

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
     * @param int|string $code code值
     * @throws Exception
     * @date  2019-5-22 21:54
     */
    public function get($code = '')
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

            } else {

            }
        }

    }
}