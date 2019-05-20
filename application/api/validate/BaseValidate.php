<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/4 0004
 * Time: 15:55
 */

namespace app\api\validate;
use app\lib\exception\ParameterException;
use think\Exception;
use think\facade\Request;
use think\Validate;

class BaseValidate extends Validate
{

    /**
     * 根据传递过来的参数进行校验
     * @request $request   获取http头部信息
     * @return  bool
     * @throws  Exception
     */
    public function goCheck()
    {
        //过去http传来的参数
        $request = Request::instance();
        //参数
        $params  = $request->param();

        //进行验证
        $result  = $this->check($params);
        if(!$result){
            //抛出验证器异常
            $e = new ParameterException([
                'msg'  => $this->error
            ]);

            throw  $e;
        }else{
            return true;
        }
    }


    /**
     * 校验ID是否为正整数
     * @int $value ID
     * @return bool|string
     * @date  2019-5-13 20:46
     */
    protected function isPositiveInteger($value,$rule='',$data='',$field='')
    {
        if(is_numeric($value) && is_int($value + 0) && ($value +0) >0 ){
            return true;
        }else{
            return false;
            //return $field."必须是正整数";
        }
    }


}