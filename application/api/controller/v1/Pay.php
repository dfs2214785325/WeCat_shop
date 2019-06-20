<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/6/12 0012
 * Time: 22:51
 */

namespace app\api\controller\v1;

use app\api\service\Pay as PayService;
use app\api\validate\IDMustBePositiveInt;

class Pay extends Base
{
    // 先检测用户权限
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOder']
    ];

    /**
     * 获取微信支付预订单
     * @param string $id 订单编号ID
     * @return void
     * @date  2019-6-12
     */
    public function getPreOrder($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();

        $pay = new PayService($id);
        $pay->pay();

    }
}