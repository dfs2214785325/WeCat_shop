<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/6/8 0008
 * Time: 0:56
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use think\Exception;

class Order
{
    // 订单的商品列表，客户端传递过来的参数
    protected $oProducts;
    // 数据库中的商品信息（包括库存）
    protected $products;
    // 会员uid
    protected $uid;

    //
    public function place(int $uid, array $oProducts)
    {
        // oProducts和products作对比
        $this->oProducts = $oProducts;
        $this->products = $this->getProductByOrder($oProducts);
        $this->uid = $uid;

        // 获取订单状态
        $status = $this->getOrderStatus();
        if (!$status['pass']) {
            $status['order_id'] = -1;
            return $status;
        }

        // 开始创建订单快照
        $orderSnap = $this->snapOrder($status);
    }


    /**
     * 生成订单快照
     * @date  2019-6-9
     */
    private function snapOrder(array $status): array
    {
        // 订单快照信息
        $snap = [
            'orderPrice' => 0,      //订单价格
            'totalCount' => 0,      //订单商品数量
            'pStatus' => [],
            'snapAddress' => null,
            'snapName' => '',
            'snapImg' => ''
        ];

        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];
        if (count($this->products) > 1) {
            $snap['snapName'] = $this->products[0]['name'] . '等';
        }

        return $snap;
    }


    /**
     * 创建订单
     * @param array $snap 订单快照信息
     * @date  2019-6-9
     */
    private function createOrder(array $snap)
    {
        try {
            $orderNo = self::makeOrderNo();

            // 实例化model层的order
            $order = new \app\api\model\Order();
            $order->order_no = $orderNo;
            $order->user_id = $this->uid;
            $order->total_price = $snap['orderPrice'];
            $order->tatal_count = $snap['total_count'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);
            $order->save();

            // 写入order_product表
            $orderID = $order->id;
            $createTime = $order->create_time;
            foreach ($this->oProducts as &$p) {
                $p['order_id'] = $orderID;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);

            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $createTime;];

        } catch (Exception $ex) {
            return $ex;
        }
    }

    /**
     * 获取订单真实状态
     * @date  2019-6-8
     */
    private function getOrderStatus(): array
    {
        // 订单整体信息
        $status = [
            'pass' => true,         //订单状态正常
            'orderPrice' => 0,      //总价
            'totalCount' => 0,      //商品总数量
            'pStatusArray' => [],   //存放商品信息
        ];

        // 取出商品数据
        foreach ($this->oProducts as $oProduct) {
            $pStatus = $this->getProductStatus($oProduct['product_id'], $oProduct['count'], $this->oProducts);
            if (!$pStatus['haveStock']) {
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['count'];
            // 取出所有商品信息
            array_push($status['pStatusArray'], $pStatus);
        }

        return $status;
    }


    /**
     * 获取某一商品的详细参数
     * @return array
     * @param integer $oPID 商品ID
     * @param integer $oCount 购买数量
     * @param array $products 商品列表
     * @date  2019-6-8
     */
    private function getProductStatus(int $oPID, int $oCount, array $products): array
    {
        // 商品下标
        $pIndex = -1;

        // 订单某一商品详情
        $pStatus = [
            'id' => null,           //商品ID
            'haveStock' => false,   //库存状态：大于0则为true
            'count' => 0,           //购买数量
            'name' => '',           //商品名称
            'totalPrice' => 0.00     //购买总价
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
            // 返回对应的商品信息,用新数组$product存放下标为$pIndex的$products的元素
            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['name'] = $product['name'];
            $pStatus['count'] = $oCount;
            $pStatus['totalPrice'] = $pStatus['price'] * $oCount;
            if ($product['stock'] - $oCount >= 0) {
                $pStatus['haveStock'] = true;
            }
        }

        return $pStatus;
    }


    /**
     * 根据商品列表查找商品详情
     * @param array $oProducts 商品列表
     * @date  2019-6-8
     */
    private function getProductByOrder($oProducts): array
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


    /**
     * 查询用户地址详情
     * @date  2019-6-9
     */
    private function getUserAddress()
    {
        $userAddress = UserAddress::where('user_id', $this->uid)->find();
        if (!$userAddress) {
            throw new UserException([
                'msg' => '用户地址不存在，下单失败！',
                'errorCode' => 60001
            ]);
        }

        return $userAddress->toArray();
    }

    /**
     * 生成随机订单号
     * @date  2019-6-9
     */
    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L');
        $orderSn = $yCode[intval(date('Y')) - 2019] . strtoupper(dechex(date('m'))) . date('d') . substr(
                time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));

        return $orderSn;
    }
}
