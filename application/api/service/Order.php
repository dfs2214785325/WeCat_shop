<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/6/8 0008
 * Time: 0:56
 */

namespace app\api\service;


use app\api\model\Product;
use app\lib\exception\OrderException;

class Order
{
    // 订单的商品列表，客户端传递过来的参数
    protected $oProducts;
    // 数据库中的商品信息（包括库存）
    protected $products;
    // 会员uid
    protected $uid;


    public function place(int $uid, array $oProducts)
    {
        $this->oProducts = $oProducts;
        $this->products = $this->getProductByOrder($oProducts);
        $this->uid = $uid;
    }

    /**
     * 获取订单真实状态
     * @date  2019-6-8
     */
    private function getOrderStatus()
    {
        //订单整体信息
        $status = [
            // 订单状态正常
            'pass' => true,
            // 总价
            'orderPrice' => 0,
            // 存放商品信息
            'pStatusArrat' => [],
        ];

        //取出商品数据
        foreach ($this->oProducts as $oProduct) {

        }
    }

    /**
     * 获取某一商品的详细参数
     * @param integer $oPID 商品ID
     * @param integer $oCount 购买数量
     * @param array $products 商品列表
     * @date  2019-6-8
     */
    private function getProductStatus(int $oPID, int $oCount, array $products)
    {
        //商品下标
        $pIndex = -1;
        //订单某一商品详情
        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'totalPrice' = 0.00
        ];

        // 取出$products中与之对应的product_id元素下标 作为$pIndex的下标（用于校验）
        for ($i = 0; $i < count($products); $i++) {
            if ($oPID == $products[$i]['id']) {
                $pIndex = $i;
            }
        }

        // 假如商品序号没有变,即procudt_id是不存在的
        if ($pIndex == -1) {
            throw new OrderException(['msg' => 'id为' . $oPID . '的商品不存在，创建订单失败！']);
        } else {
            // 返回对应的商品信息
            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
        }
    }

    /**
     * 根据商品列表查找商品详情
     * @param array $oProducts 商品列表
     * @date  2019-6-8
     */
    private function getProductByOrder($oProducts)
    {
        // 循环弹出商品ID
        $oPids = [];
        foreach ($oProducts as $items) {
            array_push($oPids, $items['product_id']);
        }

        $products = Product::all($oPids)
            ->visible(['id', 'price', 'stock', 'name', 'main_img_url'])
            ->toArray();

        return $products;
    }
}