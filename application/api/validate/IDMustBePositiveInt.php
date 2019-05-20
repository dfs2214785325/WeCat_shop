<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/4 0004
 * Time: 15:30
 */
namespace app\api\validate;

class IDMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger'
    ];

    //设置验证ID规则
    protected function isPositiveInteger($value,$rule='',$data='',$field='')
    {
        if(is_numeric($value) && is_int($value + 0) && ($value +0) >0 ){
            return true;
        }else{
            return $field."必须是正整数";
        }
    }
}