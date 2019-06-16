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

    }

    /**
     * 生成微信预订单
     * @date  2019-6-16
     */
    private function makeWxPreOrder()
    {

    }

    /**
     * 检测订单各项数据是否正常
     * @throws TokenException
     * @throws OrderException
     * @return error|bool
     * @date  2019-6-16
     */
    private function checkOrderValid()
    {
        $order = OrderModel::where('id', $this->orderID)
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
                'msg' => '改订单已支付',
                'code' => 400,
                'errorCode' => 80003
            ]);
        }

        // 将订单号存入全局变量中
        $this->orderNo = $order->order_no;

        return true;
    }

}