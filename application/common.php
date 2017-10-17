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

//获取客户端真实ip
function get_proxy_ip()
{
    $arr_ip_header = array(
        'HTTP_CDN_SRC_IP',
        'HTTP_PROXY_CLIENT_IP',
        'HTTP_WL_PROXY_CLIENT_IP',
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'REMOTE_ADDR',
    );
    $client_ip = 'unknown';
    foreach ($arr_ip_header as $key)
    {
        if (!empty($_SERVER[$key]) && strtolower($_SERVER[$key]) != 'unknown')
        {
            $client_ip = $_SERVER[$key];
            break;
        }
    }
    return $client_ip;
}


/**
 * 下载文件
 * @param string $file_url 文件路径
 * @param string $new_name 新的文件名（默认为原名）
 * @return string
 */
function download($file_url,$new_name=''){
    if(!isset($file_url)||trim($file_url)==''){
        return '500';
    }
    if(!file_exists($file_url)){ //检查文件是否存在
        return '404:文件不存在';
    }
    $file_name=basename($file_url);
    $file_type=explode('.',$file_url);
    $file_type=$file_type[count($file_type)-1];
    $file_name=trim($new_name=='')?$file_name:urlencode($new_name);
    $file_type=fopen($file_url,'r'); //打开文件
    //输入文件标签
    header("Content-type: application/octet-stream");
    header("Accept-Ranges: bytes");
    header("Accept-Length: ".filesize($file_url));
    header("Content-Disposition: attachment; filename=".$file_name);
    //输出文件内容
    echo fread($file_type,filesize($file_url));
    fclose($file_type);
}

/**
 * 根据cookie查询user表中对应的数据
 * @return array|false|PDOStatement|string|\think\Model
 */
function getUser(){
    $cookieUsername = cookie('?username')?cookie('username'):input('cookie');
    $user = db('user')->where('cookie_username',$cookieUsername)->find();
    return $user;
}

/**
 * 根据管理员姓名，找到对应user表中的数据；再根据user表中的area，找到对应的仓库数据
 * @param $name
 * @return array $storehouse
 */
function getStorehouse($name){
    $user = db('user')->where('name',$name)->find();
    $storehouse = db('storehouse')->where('area',$user['area'])->find();
    return $storehouse;
}