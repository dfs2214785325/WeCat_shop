<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/6/2 0002
 * Time: 16:07
 */

namespace app\api\model;


class UserAddress extends BaseModel
{
    //隐藏字段
    protected $hidden = ['id', 'delete_time', 'user_id'];
}