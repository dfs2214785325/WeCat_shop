<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/6/16 0016
 * Time: 22:33
 */

namespace app\lib\enum;

//订单状态枚举
class OrderStatusEnum
{
    // 待支付
    const UNPAID = 1;

    // 已支付
    const PAID = 2;

    // 已发货
    const DELIVERED = 3;

    // 已支付，但库存不足
    const PAID_BUT_OUT_OF = 4;
}