<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/7/14 0014
 * Time: 22:44
 */

namespace app\api\validate;


class AppTokenGet extends BaseValidate
{
    protected $rule = [
        'ac' => 'require|isNotEmpty',
        'se' => 'require|isNotEmpty',
    ];

}