<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::rule('think', function () {
    return ':)';
}, 'POST');

//设置一般的路由访问格式
//Route::rule('路由表达式','路由地址','请求类型','参数（数组）','变量规则（数组）');
Route::get('hello/:name', 'index/hello');
Route::rule('hello', 'index/index/test', 'GET|POST');

return [


];
