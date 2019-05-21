<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/21 0021
 * Time: 20:14
 */

namespace app\api\model;

use think\Model;

class Category extends Model
{
    //隐藏字段
    protected $hidden = ['delete_time','update_time','create_time'];

    /**
     * 关联Image表，一对一
     * @date  2019-5-21 20:17
     */
    public function Img()
    {
        return $this->belongsTo('Image','topic_img_id','id');
    }


}