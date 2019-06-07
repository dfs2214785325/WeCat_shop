<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/6/8 0008
 * Time: 0:35
 */

namespace app\api\validate;

use app\lib\exception\ParameterException;

class OrderPlace extends BaseValidate
{
    //主体校验
    protected $rule = [
        'products' => 'checkProducts',
    ];

    //子校验
    protected $singleRule = [
        'product_id' => 'require|isPositiveInteger',
        'count' => 'require|isPositiveInteger'
    ];

    /**
     * 验证商品参数
     * @parma array $values 商品参数
     * @date  2019-6-8
     */
    protected function checkProducts($values)
    {
        if (empty($values)) {
            throw new ParameterException(['msg' => '商品列表不能为空！']);
        }
        if (!in_array($values)) {
            throw new ParameterException(['msg' => '商品列表参数异常']);
        }
        foreach ($values as $value) {
            $this->checkProduct($value);
        }

        return true;
    }

    /**
     * 验证商品参数中的商品ID及数量
     * @param array $values 商品参数
     * @date  2019-6-8
     */
    protected function checkProduct($values)
    {
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($values);
        if (!$result) {
            throw new ParameterException(['msg' => '商品列表参数错误！']);
        }
    }
}