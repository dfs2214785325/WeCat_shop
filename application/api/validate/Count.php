<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/18 0018
 * Time: 21:43
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule = [
        'count' => 'isPositiveInteger|between:1,15',
    ];
}