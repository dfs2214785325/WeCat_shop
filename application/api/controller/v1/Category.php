<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/21 0021
 * Time: 20:13
 */

namespace app\api\controller\v1;
use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;

class Category
{
    /**
     * 获取所有分类数据
     * @url
     * @date  2019-5-21 20:13
     */
    public function getAllCategories()
    {
        $categories = CategoryModel::all([],'img');

        if($categories->isEmpty()){
            throw new CategoryException();
        }

        return $categories;
    }
}