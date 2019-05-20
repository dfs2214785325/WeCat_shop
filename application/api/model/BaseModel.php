<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/12 0012
 * Time: 22:00
 */

namespace app\api\model;
use think\Model;

class BaseModel extends Model
{

    /**
     * @param $value  提取的字段值
     * @param $data   所要读取字段的所有值
     * @return string 更新后的读取值
     * @date: 2019-5-12 22:02
     */
    protected function prefixImageUrl($value,$data)
    {
        $finalUrl = $value;

        if($data['from'] == 1){
            //代表本地图片库
            $finalUrl = config('setting.img_prefix').$value;
        }

        return $finalUrl;
    }


}