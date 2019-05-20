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


Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

//设置一般的路由访问格式
//Route::rule('路由表达式','路由地址','请求类型','参数（数组）','变量规则（数组）');
//Route::get('hello/:name', 'index/hello');
//Route::rule('hello','index/index/test','GET|POST');


//（切记不要传命名空间）
//banner
Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');
//theme
Route::get('api/:version/theme','api/:version.Theme/getSimpleList');
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');
//product
Route::get('api/:version/product/recent','api/:version.Product/getRecent');
return [


];
