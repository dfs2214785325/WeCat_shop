<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/6/4 0004
 * Time: 20:37
 */

namespace app\api\controller\v1;

use think\Controller;

class Order extends Controller
{
    /*
     * 用户在选择商品，向api提交包含它所选择的商品相关信息
     * api接口在接收到信息后，需要检查订单相关商品的库存量
     * 有库存，把订单数据存入数据库中。下单成功，返回客户信息，告诉客户端可以支付
     * 调用支付接口
     * 继续库存量检测
     * 服务器这边进行微信支付
     * 根据微信回调，返回不同的结果
     * 成功：进行库存检测并扣除库存
     * 失败：返回支付失败结果
     */

    const a;
}