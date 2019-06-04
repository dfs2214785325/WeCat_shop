<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/29 0029
 * Time: 22:55
 */

namespace app\api\controller\v1;

use app\api\model\User as UserModel;
use app\api\service\Token as TokenService;
use app\api\validate\AddressNew;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use app\lib\exception\UserException;
use app\lib\SuccessMessage;
use think\Controller;

class Address extends Controller
{
    //调用类前使用此方法(即将废弃)
    protected $beforeActionList = [
        //表示访问createOrUpdateAddress方法前，先调用checkPrimaryScope方法
        'checkPrimaryScope' => ['only' => 'createOrUpdateAddress']
    ];

    protected function checkPrimaryScope()
    {
        $scope = TokenService::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope >= ScopeEnum::User) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }

    /**
     * 新建会员收货地址
     * @url api/v1/address
     * @date  2019-5-29
     */
    public function createOrUpdateAddress()
    {
        $validate = new AddressNew();
        $validate->goCheck();

        // 根据Token或者用户uid
        $uid = TokenService::getCurrentUid();

        // 先查找用户数据,判断是否存在
        $user = UserModel::get($uid);
        if (!$user) {
            throw new UserException();
        }

        // 获取客户端传来的地址信息
        $dataArray = $validate->getDateByRule(input('post.'));

        //判断用户地址信息是否存在，从而判断是更新地址还是添加地址
        $userAddress = $user->address;
        if (!$userAddress) {
            //创建
            $user->address()->save($dataArray);
        } else {
            //更新
            $user->address->save($dataArray);
        }

        //成功就返回成功的信息
        return json(new SuccessMessage(), 201);
    }
}