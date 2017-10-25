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

//返回success提示和返回信息
function returnSuccess($message){
    return json(['state'=>'success','message'=>$message]);
}

//返回warning提示和错误信息
function returnWarning($message){
    return json(['state'=>'warning','message'=>$message]);
}

//返回error提示和错误信息
function returnError($message){
    return json(['state'=>'error','message'=>$message]);
}


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
 * 根据管理员姓名，找到对应user模型对象；再根据user表中的area，找到对应的仓库对象
 * @param $name
 * @return array $storehouse
 */
function getStorehouse($name){
    $user = db('user')->where('name',$name)->find();
    $storehouse = db('storehouse')->where('area',$user['area'])->find();
    return $storehouse;
}


/**
 * 查询输入数据是否在数据库中存在
 * @param string $table 表名
 * @param string $column 字段名
 * @param string $value 值
 * @return bool
 */
function dataIsExist($table,$column,$value){
    $res = db($table)->where($column,$value)->find();
    if(!$res) return false;
    return true;
}


