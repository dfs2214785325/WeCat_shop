<?php
/**
 * Created by PhpStorm
 * User: dfs
 * Date: 2019/5/22 0022
 * Time: 22:20
 */

use think\facade\Route;

//api路由
//（切记不要传命名空间）
//token(用post比较安全)
Route::post('api/:version/token/user', 'api/:version.Token/getToken');


//banner【轮播图】
Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');

//theme【专题】
Route::get('api/:version/theme', 'api/:version.Theme/getSimpleList');
Route::get('api/:version/theme/:id', 'api/:version.Theme/getComplexOne');

//product【商品】
Route::group('api/:version/product', function () {
    //直接写访问地址
    Route::get('/:id', 'api/:version.Product/getOne', [], ['id' => '\d+']);
    Route::get('/recent', 'api/:version.Product/getRecent');
    Route::get('/by_category', 'api/:version.Product/getAllInCategory');
});

//category【分类】
Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories');

//收货地址
Route::post('api/:version/address', 'api/:version.Address/createOrUpdateAddress');

//订单
Route::post('api/:version/order', 'api/:version.Order/placeOrder');
Route::post('api/:version/order/:id', 'api/:version.Order/getDetail', [], ['id' => '\d+']);
Route::post('api/:version/order/by_user', 'api/:version.Order/getSummaryByUser');

//微信支付
Route::post('api/:version/pay/pre_order', 'api/:version.Pay/getPreOrder');

//回调
Route::post('api/:version/pay/notify', 'api/:version.Pay/receiveNotify');
Route::post('api/:version/pay/re_notify', 'api/:version.Pay/redirectNotify');

