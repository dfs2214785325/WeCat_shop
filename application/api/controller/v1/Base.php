<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/12 0012
 * Time: 22:30
 */

namespace app\api\controller\v1;

use app\api\service\Token as TokenService;
use think\Controller;

class Base extends Controller
{

    /**
     * 检测用户权限（会员、管理员都有权限）
     * @date 2019-6-8
     */
    protected function checkPrimaryScope()
    {
        TokenService::needPrimaryScope();
    }

    /**
     * 检测用户权限（仅管理员权限）
     * @date 2019-6-8
     */
    protected function checkExclusiveScope()
    {
        TokenService::needExclusiveScope();
    }
}