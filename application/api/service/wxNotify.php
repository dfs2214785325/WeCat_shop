<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/6/22 0022
 * Time: 22:54
 */

namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\model\Product;
use app\api\service\Order as OrderService;
use app\lib\enum\OrderStatusEnum;
use think\Db;
use think\Exception;
use think\facade\Env;
use think\facade\Log;

require_once Env::get('root_path') . 'extend/WxPay/Wxpay.Api.php';

class wxNotify extends \WxPayNotify
{
    /**
     * 处理微信支付异步回调通知结果
     * @param array $objData 微信回调数组
     * @param string $msg 收集错误信息
     * @return \true回调出来完成不需要继续回调，false回调处理未完成需要继续回调|void
     * @date  2019-6-22
     */
    public function NotifyProcess($objData, $config, &$msg)
    {
        //return parent::NotifyProcess($objData, $config, $msg);
        if ($objData['result_code'] == 'SUCCESS') {
            $orderNo = $objData['out_trade_no'];
            // 开始数据库事务
            Db::startTrans();
            try {
                $order = \app\api\model\OrderModel::where('order_no', '=', $orderNo)
                    ->find();
                if ($order->status == 1) {
                    //检测库存
                    $service = new OrderService();
                    $stockStatus = $service->checkOrderStatus($order->id);
                    if ($stockStatus['pass']) {
                        // 假如有库存，那就继续操作
                        $this->updateOrderStatus($order->id, true);
                        $this->reduceStock($stockStatus);
                    } else {
                        // 没有库存了，就更新订单为无库存支付失败状态
                        $this->updateOrderStatus($order->id, false);
                    }
                }

                Db::commit();
                return true;    //返回true，则服务器不会继续发送请求了

            } catch (Exception $ex) {
                //回滚并写入日志
                Db::rollback();
                Log::record($ex, 'error');
                return false;
            }

        } else {
            // 这里返回true是因为已经知道了支付不成功，还要返回false的话微信服务器还会返回请求
            return true;
        }
    }

    /**
     * 根据订单商品状态返回值更新订单状态
     * @param int $orderID 订单ID编号
     * @param bool $success 商品状态值：true|false
     */
    private function updateOrderStatus(int $orderID, bool $success)
    {
        // 假如status为true：2，false：4
        $status = $success ? OrderStatusEnum::PAID : OrderStatusEnum::PAID_BUT_OUT_OF;
        OrderModel::where('id', '=', $orderID)
            ->update(['status' => $status]);
    }

    /**
     * 处理库存
     * @param array $stockStatus 订单商品汇总信息
     */
    private function reduceStock($stockStatus)
    {
        //循环查看订单商品状态
        foreach ($stockStatus['pStatusArray'] as $singlePStatus) {
            Product::where('id', '=', $singlePStatus['id'])
                ->dec('stock', $singlePStatus['count']);
        }
    }
}