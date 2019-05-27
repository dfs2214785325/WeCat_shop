<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/12 0012
 * Time: 22:37
 */

namespace app\api\controller\v1;

use app\api\model\Theme as ThemeModel;
use app\api\validate\IDCollection;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ThemeException;
use think\Controller;

class Theme extends Controller
{

    /**
     * 获取简单的专题
     * @url /theme?ids=id1,id2,id3,...
     * @param array $ids 多个专题ID
     * @return theme数据
     * @throws ThemeException
     * @date  2019-5-13 20:33
     */
    public function getSimpleList($ids = [])
    {
        (new IDCollection())->goCheck();

        $ids = explode(',',$ids);
        $resutl = ThemeModel::with('topicImg,headImg')->select($ids);

        if($resutl->isEmpty()){
            throw new ThemeException();
        }
        return $resutl;

    }

    /**
     * 根据ID查看专题详情
     * @url /theme/:id
     * @int $id 专题ID
     * @date  2019-5-13 21:15
     */
    public function getComplexOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();

        $theme = ThemeModel::getThemeWithProducts($id);
        if($theme->isEmpty()){
            throw new ThemeException();
        }

        return $theme;
    }
}