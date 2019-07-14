<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/7 0007
 * Time: 20:11
 */
namespace app\api\model;

class Banner extends BaseModel
{
    // php think optimize:schema

    //设置隐藏查询显示
    protected $hidden = ['update_time','delete_time'];

    /**
     * banner_item表，一对多
     * @return \think\model\relation\HasMany
     */
    public function items()
    {
        //hasMany(关联表,关联表外键,当前表主键)
        return $this->hasMany('BannerItem','banner_id','id');
    }


    /**
     * 根据ID获取banner信息
     * @param int $id
     * @return string
     */
    public static function getBannerByID(int $id)
    {
        $bannerSqlfetch = self::with(['items','items.img'])
            ->all($id);

        return $bannerSqlfetch;
    }
}