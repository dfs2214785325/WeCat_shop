<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * curl封装 GET请求
 * @param string $url get请求地址
 * @param int $http_code 返回的http状态码
 * @return string 拼装好的请求地址
 * @date  2019-5-22 21:56
 */
function curl_get($url, &$httpCode = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //不做证书验证,部署在linux下则改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return $file_contents;

}

/**
 * curl封装 POST请求
 * @param $url 请求地址
 * @param string $rowData 原始的xml地址
 */
function curl_post_row($url, $rowData)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $rowData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type:text'));

    $data = curl_exec($ch);
    curl_close($ch);

    return ($data);
}

/**
 * 返回一组随机字符
 * @date  2019-5-24
 */
function getRandChars(int $length): string
{
    $str = '';
    $strPol = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $max = strlen($strPol) - 1;

    for ($i = 1; $i < $length; $i++) {
        $str .= $strPol[rand(0, $max)];
    }

    return $str;

}
