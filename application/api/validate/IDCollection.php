<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/13 0013
 * Time: 20:37
 */
namespace app\api\validate;



class IDCollection extends BaseValidate
{
    protected $rule = [
        'ids' => 'require|checkIDs'
    ];

    protected $message = [
        'ids' => 'ids参数必须以‘,’分隔的多个正整数'
    ];


    /**
     * 验证ID格式是否正确
     * @value id数组
     * @date 2019-5-13 20:40
     */
    protected function checkIDs($value)
    {
        $value = explode(',',$value);
        if(empty($value)){ return false; }

        foreach($value as $id){
            if(!$this->isPositiveInteger($id)){
                return false;
            }
        }

        return true;

    }
}