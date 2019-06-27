<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/6/9 0009
 * Time: 15:45
 */

namespace app\api\model;

use think\exception\DbException;

class Order extends BaseModel
{
    protected $hidden = ['user_id', 'delete_time', 'update_time'];
    protected $autoWriteTimestamp = true;

    /**
     * 查询用户订单简要信息
     * @param int $uid 用户ID
     * @param int $page
     * @param int $size
     * @return array|object 订单数组对象
     * @throws DbException
     */
    public static function getSummaryByUser(int $uid, int $page = 1, int $size)
    {
        // 使用简介模式，不考虑总数量
        try {
            $pagingData = self::where('user_id', '=', $uid)
                ->order('create_time desc')
                ->paginate($size, true, ['page' => $page]);

            return $pagingData;

        } catch (DbException $e) {
            throw $e;
        }
    }
}