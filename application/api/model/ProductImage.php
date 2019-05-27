<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/27 0027
 * Time: 22:42
 */

namespace app\api\model;


class ProductImage extends BaseModel
{
    //隐藏字段
    protected $hidden = ['img_id', 'delete_time', 'product_id'];

    /**
     * 一对一关联image
     * @return \think\model\relation\BelongsTo
     * @date ct
     */
    public function imgUrl()
    {
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}