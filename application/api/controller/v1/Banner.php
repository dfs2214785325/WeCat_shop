<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/4 0004
 * Time: 13:08
 */
namespace app\api\controller\v1;

use app\api\model\Banner as BannerModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\BannerMissException;


class Banner
{
    /**
     * 获取指定ID的banner信息
     * @url   /banner/:id
     * @http  GET
     * @return string 【 banner信息 】
     * @param int $id 【 bannerID 】
     * @throws BannerMissException
     * @throws \think\Exception
     */
    public function getBanner($id)
    {
        // php think optimize:route(用户第一次查询后，在缓存不过期的情况下，都用这个缓存数据返回给用户)

       //AOP 面向切面编程~
        (new IDMustBePositiveInt())->goCheck();

        $banner = BannerModel::getBannerByID($id);
        if($banner->isEmpty()){
            throw new BannerMissException();
        }

        return $banner;

    }
}