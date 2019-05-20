<?php

namespace app\api\model;

class BannerItem extends BaseModel
{
    //隐藏的字段值
    protected $hidden = ['id','img_id','banner_id','update_time','delete_time'];

    /**
     * 关联image表，一对一
     * @author dfs
     * @date  2019-5-12 22:49
     */
    public function img()
    {
        //关联一对一,belongsTo(关联表,关联表外键,当前表主键)
        return $this->belongsTo('Image','img_id','id');
    }

}
