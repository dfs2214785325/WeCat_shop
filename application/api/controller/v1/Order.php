<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/6/4 0004
 * Time: 20:37
 */

namespace app\api\controller\v1;

use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\api\service\Token as TokenModel;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\OrderPlace;
use app\api\validate\PagingParameter;
use app\lib\exception\OrderException;

class Order extends Base
{
    /*
     * 用户在选择商品，向api提交包含它所选择的商品相关信息
     * api接口在接收到信息后，需要检查订单相关商品的库存量
     * 有库存，把订单数据存入数据库中。下单成功，返回客户信息，告诉客户端可以支付
     * 调用支付接口
     * 继续库存量检测
     * 服务器这边进行微信支付
     * 小程序根据服务器返回结果拉起微信支付
     * 根据微信回调，返回不同的结果（异步，需要公网可访问）
     * 成功：进行库存检测并扣除库存
     * 失败：返回支付失败结果
     */

    //做一次库存量检测
    //创建订单
    //根据订单购买数量 减库存 --预扣除(不减)
    //如果用户支付订单--真正的减库存（如果规定时间如30min内不支付，则还原库存）

    //NO.1 写一个定时器，每隔一段时间遍历数据库，找到超时的订单，再还原(还可以用：脚本、linux crontab)
    /*NO.2 任务队列:[
        订单任务加入到任务队列中
        如：redis队列 -- 存入redis缓存设置定时器，定时器过期则触发一个是失效事件
    ]
    */

    //调用类前使用此方法(即将废弃)
    protected $beforeActionList = [
        //表示访问placeOrder方法前，先调用checkExclusiveScope方法
        'checkExclusiveScope' => ['only' => 'placeOrder'],
        'checkPrimaryScope' => ['only' => 'getSummaryByUser,getDetail'],
    ];


    /**
     * 创建订单
     * @date  2019-6-8
     * @return array
     */
    public function placeOrder()
    {
        (new OrderPlace())->goCheck();

        //获取数组参数，必须post.参数名/a
        $products = input('post.products/a');
        $uid = TokenModel::getCurrentUid();

        $order = new OrderService();
        $status = $order->place($uid, $products);

        return $status;
    }

    /**
     * 根据页码数查询用户订单简要信息
     * @param int $page 起始页
     * @param int $size 显示订单的最大数量
     * @date  2019-6-28
     */
    public function getSummaryByUser($page = 1, $size = 15)
    {
        (new PagingParameter())->goCheck();

        $uid = TokenModel::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByUser($uid, $page, $size);

        // 假如返回为空数组
        if ($pagingOrders->isEmpty()) {
            return [
                'data' => [],
                'current_page' => $pagingOrders->getCurrentPage()
            ];
        }

        return [
            'data' => $pagingOrders->hidden(['id', 'snap_items', 'snap_address', 'prepay_id'])->toArray(),
            'current_page' => $pagingOrders->getCurrentPage()
        ];
    }

    /**
     * 查看订单详情信息
     * @param int $id 订单编号
     * @return 正确返回array，错误返回异常
     * @date  2019-6-30
     */
    public function getDetail($id)
    {
        (new IDMustBePositiveInt())->goCheck();

        $orderDetail = OrderModel::get($id);
        if (!$orderDetail) {
            throw new OrderException();
        }

        return $orderDetail
            ->hidden(['prepay_id']);
    }

    /**
     * 删除订单
     * @date  2019-6-8
     */
    public function deleteOrder()
    {

    }
}