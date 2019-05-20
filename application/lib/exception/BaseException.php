<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/7 0007
 * Time: 21:09
 */
namespace app\lib\exception;

use think\Exception;

class BaseException extends Exception
{
    //HTTP 状态码 404,200
    public $code = 400;
    //错误信息
    public $msg  = "error";
    //自定义错误码
    public $errorCode = 10000;


    /**
     * 创造标识码
     * BaseException constructor.
     * @param array $params
     */
    public function __construct(array $params =[])
    {
        //假如有新code
        if(array_key_exists('code',$params)){
            $this->code = $params['code'];
        }

        //假如有新的msg
        if(array_key_exists('msg',$params)){
            $this->msg = $params['msg'];
        }

        //假如有新的errorCode
        if(array_key_exists('errorCode',$params)){
            $this->errorCode = $params['errorCode'];
        }
    }

}