<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/29 0029
 * Time: 22:58
 */

namespace app\api\validate;


class AddressNew extends BaseValidate
{
    //验证规则
    protected $rule = [
        'name' => 'require|isNoEmpty',
        'mobile' => 'require|isMobile',
        'province' => 'require|isNoEmpty',
        'city' => 'require|isNoEmpty',
        'country' => 'require|isNoEmpty',
        'detail' => 'require|isNoEmpty',
    ];
}