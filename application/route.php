<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

//权限认证Auth类
Route::rule([
    //logout页面
    'logout' =>  'index/Auth/logout',
    //login页面，只允许post请求
    'login'  =>  ['index/Auth/login',['method'=>'post']],
    //权限认证，只允许post方式请求
    'checkauth' =>  ['index/Auth/checkAuth',['method'=>'post|get']],
]);

//装维人员管理Staff控制器，只允许post请求
Route::rule([
    //添加装维人员
    'staff/add'  =>  'index/Staff/add',
    //查看所有装维人员
    'staff/check' =>  'index/Staff/check',
    //查看某个装维人员详情
    'staff/detail' =>  'index/Staff/detail',
]);
