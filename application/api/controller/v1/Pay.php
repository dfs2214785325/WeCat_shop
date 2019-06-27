<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/6/12 0012
 * Time: 22:51
 */

namespace app\api\controller\v1;

use app\api\service\Pay as PayService;
use app\api\service\wxNotify;
use app\api\validate\IDMustBePositiveInt;

class Pay extends Base
{
    // 先检测用户权限
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOder']
    ];

    /**
     * 获取微信预订单信息
     * @return array
     * @date  2019-6-22
     */
    public function getPreOrder($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();

        $pay = new PayService($id);

        return $pay->pay();
    }

    /**
     * 回调地址
     * @date  2019-6-28
     */
    public function redirectNotify()
    {
        /*1、检查库存量
         *2、更新订单的状态
         *3、减库存（根据order_product表）
         * 如果成功处理，返回success，否则返回没有成功处理
         */

        // 数据库库存减去相应的订单商品数量
        $notify = new wxNotify();
        $notify->Handle($config);      //调用父类的Handle
    }

    /**
     * 微信支付异步回调
     * @date  2019-6-22
     */
    public function receiveNotify()
    {
        $xmlData = file_get_contents('php://input');
        $result = curl_post_row('回调地址', $xmlData);
    }
}