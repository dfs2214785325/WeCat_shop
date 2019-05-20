<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/12 0012
 * Time: 22:35
 */

namespace app\api\model;


class Theme extends BaseModel
{
    //隐藏信息
    protected $hidden = [ 'delete_time','update_time','topic_img_id','head_img_id' ];

    /**
     * 关联image表，一对一
     * @date: 2019-5-12 22:39
     */
    public function topicImg()
    {
        //关联表模型，关联外键，该表主键
        return $this->belongsTo('Image','topic_img_id','id');
    }


    /**
     * 关联image表，一对一
     * @date: 2019-5-12 22:39
     */
    public function headImg()
    {
        //一对一，关联模型，外键，当前模型主键
        return $this->belongsTo('Image','head_img_id','id');
    }


    /**
     * 关联product表，多对多
     * @date  2019-5-13 21:28
     */
    public function products()
    {
        //关联表模型，中间键表名，关联外键，本模型主键
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }


    /**
     * 获取专题下的商品目
     * @date  2019-5-16 18:25
     */
    public static function getThemeWithProducts(int $id)
    {
        $theme = self::with('products,topicImg,headImg')->find($id);

        return $theme;
    }
}