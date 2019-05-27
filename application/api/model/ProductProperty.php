<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/27 0027
 * Time: 22:42
 */

namespace app\api\model;


class ProductProperty extends BaseModel
{
    //隐藏字段
    protected $hidden = ['id', 'delete_time', 'product_id'];

}