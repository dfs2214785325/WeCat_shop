<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/6/12 0012
 * Time: 22:51
 */

namespace app\api\controller\v1;

class Pay extends Base
{
    // 先检测用户权限
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOder']
    ];

    /**
     * 获取微信支付预订单
     * @return
     * @date  2019-6-12
     */
    public function getPreOder()
    {

    }
}