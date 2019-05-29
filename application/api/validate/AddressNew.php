<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/29 0029
 * Time: 22:58
 */

namespace app\api\validate;


class AddressNew
{
    //验证规则
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'mobile' => 'require|length:11|isNotEmpty',
        'province' => 'require|isNotEmpty',
        'city' => 'require|isNotEmpty',
        'country' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty',
    ];
}