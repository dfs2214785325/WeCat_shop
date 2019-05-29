<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/12 0012
 * Time: 22:34
 */

namespace app\api\model;

class Product extends BaseModel
{

    //设置隐藏属性
    protected $hidden = [ 'delete_time','main_img_id','from','category_id','create_time','update_time'];
    //...pivot不能隐藏

    /**
     * 替换图片路径
     * @param string $value 图片路径
     * @param array  $data  读取获得的数组
     * @return 替换图片路径更新后的值
     * @date  2019-5-16 21:19
     */
    public function getMainImgUrlAttr($value,$data)
    {
        //一对一
        return $this->prefixImageUrl($value,$data);
    }

    /**
     * Product_image 一对多关联image
     * @date  2019-5-27 22:39
     */
    public function imgs()
    {
        return $this->hasMany('ProductImage', 'product_id', 'id');
    }

    /**
     * Product_property 一对多关联图片image
     * @date  2019-5-27 22:39
     */
    public function properties()
    {
        return $this->hasMany('ProductProperty', 'product_id', 'id');
    }

    /**
     * 获取相应数量的新增商品信息
     * @param  int $count 数量
     * @return array|\PDOStatement|string|\think\Collection
     * @date   2019-5-18 21:51
     */
    public static function getMostRecent(int $count)
    {
        $products = self::limit($count)->order('create_time desc')->select();

        return $products;
    }

    /**
     * 查询分类下的商品信息目
     * @param int $categoryID 分类ID
     * @return array|string 查询结果
     * @date  2019-5-27 22:30
     */
    public static function getProductsByCategory(int $categoryID)
    {
        $products = self::where('category_id',$categoryID)->select();

        return $products;
    }

    /**
     * 查询指定ID的商品信息
     * @param int $id 商品ID
     * @return array|null 查询结果
     * @date  2019-5-27 22:31
     */
    public static function getProductDetail(int $id)
    {
        //使用闭包就可以写出很多复杂的sql语句了
        $product = self::with(['imgs' => function ($query) {
            $query->with('imgUrl')->order('order ');
        }, 'properties'])->find($id);

        return $product;
    }
}