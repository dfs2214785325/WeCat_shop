<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/18 0018
 * Time: 21:40
 */

namespace app\api\controller\v1;

use app\lib\exception\ProductException;
use think\Controller;
use app\api\validate\Count;
use app\api\model\Product as ProductModel;

class Product extends Controller
{

    /**
     * 获取新增商品的信息
     * @url   product/recent?count=15
     * @param int $count 需要显示数据的数量
     * @throws ProductException
     * @return array||string
     * @date  2019-5-18 21:48
     */
    public function getRecent($count=15)
    {
        (new Count())->goCheck();

        $products = ProductModel::getMostRecent($count);
        if(!$products){
            throw  new ProductException();
        }

        return $products;
    }
}