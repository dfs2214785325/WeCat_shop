<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/6/16 0016
 * Time: 20:15
 */

namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use think\facade\Log;

require EXTEND_PATH . 'WxPay.WxPay.Api.php';

class Pay
{
    // 订单编号ID
    private $orderID;
    // 订单号
    private $orderNo;

    function __construct($orderID)
    {
        if (!$orderID) {
            throw new Exception('订单号不能为空');
        }

        $this->orderID = $orderID;
    }


    /**
     * 主方法-微信支付
     * 流程：检测订单状态及参数->生成预订单->微信支付(统一下单)->微信回调(公网IP可访问地址)
     * @return null
     * @date  2019-6-16
     */
    public function pay()
    {
        // 检测订单状态
        $this->checkOrderValid();
        // 检测库存
        $orderService = new OrderService();
        $status = $orderService->checkOrderStatus($this->orderID);

        if (!$status['pass']) {
            return $status;
        }

        return $this->makeWxPreOrder($status['orderPrice'])

    }

    /**
     * 生成微信预订单
     * @param int $totalPrice 订单总金额
     */
    private function makeWxPreOrder($totalPrice)
    {
        //获取openid
        $openid = Token::getCurrentTokenVar('openid');
        if (!$openid) {
            throw new TokenException();
        }

        // 调用微信第三方类
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNo);
        $wxOrderData->SetTrade_type('JSPAI');
        $wxOrderData->SetTotal_fee($totalPrice * 100);
        $wxOrderData->SetBody('零食商贩');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url(config('secret.notify_yrl'));

        return $this->getPaySignature($wxOrderData);
    }


    /**
     * 调用微信支付-统一下单接口
     * @param array $wxOrderData 微信预订单信息
     */
    private function getPaySignature($wxOrderData)
    {
        // 调用统一下单接口
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        // 判断微信返回参数
        if ($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS') {
            //错误的话就写入日志当中
            Log::record($wxOrder, 'error');
            Log::record('获取预支付订单失败', 'error');
        }

        //当return_code & result_code 都为success才会返回
        //prepay_id
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);


        //return null;    // 调试的时候可以返回$wxOrder,让客户端看得见数据信息
        return $signature;
    }

    /**
     * 封装支付签名参数
     * @param array $wxOrder 微信参数数组
     */
    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());    // 强制int类型转换为string类型

        $rand = md5(time() . mt_rand(0, 1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id=' . $wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');

        $sign = $jsApiPayData->MakeSign();
        $rawData = $jsApiPayData->GetValues();
        $rawData['paySing'] = $sign;

        //因为sdk中有了重复的appid封装，所以就删除重复值
        unset($rawData['appId']);

        return $rawData;
    }

    /**
     * 存储微信成功返回信息的预支付ID
     * @param array $wxOrder 微信返回数据
     */
    private function recordPreOrder($wxOrder)
    {
        OrderModel::where('id', $this->orderID)
            ->update(['prepay_id' => $wxOrder['prepay_id']]);
    }

    /**
     * 检测订单各项数据是否正常
     * @throws TokenException
     * @throws OrderException
     */
    private function checkOrderValid()
    {
        $order = OrderModel::where('id', '=', $this->orderID)
            ->find();

        // 检测订单号是否存在
        if (!$order) {
            throw new OrderException();
        }

        // 检测订单号与当前用户是否配
        if (!Token::isValidOperate($order->user_id)) {
            throw new TokenException([
                'msg' => '订单与用户不匹配',
                'errorCode' => 10003
            ]);
        }

        // 检测订单支付状态
        if ($order->status != OrderStatusEnum::UNPAID) {
            throw new OrderException([
                'msg' => '该订单已支付',
                'code' => 400,
                'errorCode' => 80003
            ]);
        }

        // 将订单号存入全局变量中
        $this->orderNo = $order->order_no;

        return true;
    }

}