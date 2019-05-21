<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/7 0007
 * Time: 21:06
 */
namespace app\lib\exception;

use Exception;
use think\exception\Handle;
use think\facade\Log;
use think\facade\Request;

class ExceptionHandler extends Handle
{
    private $code;

    private $msg;

    private $errorCode;

    /**
     * 自定义render
     * @200  查询成功
     * @201  post资源创建成功
     * @202  put更新成功
     * @400 参数错误
     * @401 未授权
     * @403 当前资源禁止或越权操作
     * @500 未知错误
     * @502 服务器错误
     * @POST: 创建
     * @PUT:  更新
     * @GET:  查询
     * @DELETE: 删除
     * ====================<<>>========================
     * @param Exception $e
     * @return \think\Response|\think\response\Json
     */
    public function render(Exception $e)
    {
        if($e instanceof BaseException){
            //假如都是继承于BaseException，则返回自己的异常类信息
            $this->code      = $e->code;
            $this->msg       = $e->msg;
            $this->errorCode = $e->errorCode;

        }else{
            if( config('app_debug')){
                //开发阶段的话，就显示tp自己的异常状态
                return parent::render($e);
            }else{
                $this->code      =  500;
                $this->msg       = "server error";
                $this->errorCode =  999;
                $this->recordErrorLog($e);
            }
        }

        $request = Request::instance();

        $result = [
            'msg' => $this->msg,
            'errorCode' => $this->errorCode,
            //调试下用就好了，不然很不安全的
            'request_url' => $request->url()
        ];

        return json($result,$this->code);

    }


    //日志写入
    private function recordErrorLog(Exception $e)
    {
        Log::record($e->getMessage(),'error');
    }

}