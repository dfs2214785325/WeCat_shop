<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/6/27 0027
 * Time: 22:55
 */

namespace app\api\validate;


class PagingParameter extends BaseValidate
{
    protected $rule = [
        'page' => 'isPositiveInteger',
        'size' => 'isPositiveInteger'
    ];

    protected $message = [
        'page' => '分页参数必须为正整数',
        'size' => '分页参数必须为正整数',
    ];
}