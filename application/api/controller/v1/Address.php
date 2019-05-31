<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/29 0029
 * Time: 22:55
 */

namespace app\api\controller\v1;

use app\api\service\Token as TokenService;
use app\api\validate\AddressNew;

class Address
{
    /**
     * 新建会员收货地址
     * @date  2019-5-29
     */
    public function createOrUpdateAddress()
    {
        (new AddressNew())->goCheck();

        // 根据Token或者用户uid
        $uid = TokenService::getCurrentUid();

        // 查找用户数据,判断是否存在

        // 获取客户端传来的地址信息

        //判断用户地址信息是否存在，从而判断是更新地址还是添加地址


    }
}