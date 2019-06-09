<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/6/9 0009
 * Time: 15:45
 */

namespace app\api\model;


class Order extends BaseModel
{
    protected $hidden = ['user_id', 'delete_time', 'update_time'];
}