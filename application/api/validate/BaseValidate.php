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
     * 根据验证器rule规则过滤非法参数
     * @return array
     * @date  2019-6-2
     */
    public function getDateByRule(array $data)
    {
        if (array_key_exists('user_id', $data) || array_key_exists('uid', $data)) {
            //非法传入uid或者user_id
            throw new ParameterException([
                'msg' => "参数中含有非法参数名",
            ]);
        }

        $newArray = [];
        foreach ($this->rule as $key => $value) {
            $newArray[$key] = $data[$key];
        }

        return $newArray;
    }


    /**
     * 校验ID是否为正整数
     * @param int $value ID
     * @return bool|string
     * @date  2019-5-13 20:46
     */
    protected function isPositiveInteger($value,$rule='',$data='',$field='')
    {
        if(is_numeric($value) && is_int($value + 0) && ($value +0) >0 ){
            return true;
        }else{
            return false;

        }
    }

    /**
     * 判断code是否位空值
     * @param int $value code验证值
     * @return bool
     * @date  2019-5-22 13:27
     */
    protected function isNoEmpty($value,$rule='',$data='',$field='')
    {
        if(empty($value)){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 判断是否是正确的电话值
     * @param string $value 电话
     * @date  2019-6-2
     */
    protected function isMobile(string $value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}