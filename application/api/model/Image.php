<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/12 0012
 * Time: 13:07
 */

namespace app\api\model;

class Image extends BaseModel
{
    //隐藏的字段值
    protected $hidden = ['id','from','delete_time','update_time'];

    /**
     * @param $value    读取值
     * @param $data     读取值数组
     * @return string   更新后的读取值
     * author: dfs
     * date: 2019-5-12 22:07
     */
    public function getUrlAttr($value,$data)
    {
        return $this->prefixImageUrl($value,$data);
    }

}
