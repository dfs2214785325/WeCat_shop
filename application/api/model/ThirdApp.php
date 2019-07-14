<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/7/14 0014
 * Time: 22:51
 */

namespace app\api\model;


class ThirdApp extends BaseModel
{
    /**
     * 检测appid和secret
     * @date  2019-7-14
     */
    public static function check($ac ,$se){
        $app = self::where('app_id',$ac)
            ->where('app_secret',$se)
            ->select();

        return $app;
    }
}