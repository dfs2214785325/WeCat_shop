<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/18 0018
 * Time: 21:40
 */

namespace app\api\controller\v1;

use app\api\model\Product as ProductModel;
use app\api\validate\Count;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ProductException;
use think\Controller;

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
    public function getRecent($count = 15)
    {
        (new Count())->goCheck();

        $products = ProductModel::getMostRecent($count);
        if(!$products){
            throw  new ProductException();
        }

        //临时隐藏字段
        $products = $products->hidden(['summary']);

        return $products;
    }


    /**
     * 获取某一分类下的所有商品信息
     * @url    product/by_category?id=2
     * @param  int $id 分类ID
     * @return array|\PDOStatement|string|\think\Collection
     * @throws ProductException
     * @date  2019-5-21 21:12
     */
    public function getAllInCategory($id)
    {
        (new IDMustBePositiveInt())->goCheck();

        $products = ProductModel::getProductsByCategory($id);
        if($products->isEmpty()){
            throw new ProductException();
        }

        //临时隐藏字段
        $products->hidden(['summary']);

        return $products;
    }


    /**
     * 获取对应ID的商品信息
     * @param int $id
     * @date  2019-5-27
     */
    public function getOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModel::getProductDetail($id);
        if (!$product) {
            throw new ProductException();
        }

        return $product;

    }
}