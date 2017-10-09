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
Route::post([
    //logout页面
    'logout' =>  'index/Auth/logout',
    //login页面，只允许post请求
    'login'  =>  ['index/Auth/login',['method'=>'post']],
    //权限认证，只允许post方式请求
    'checkauth' =>  ['index/Auth/checkAuth',['method'=>'post']],
]);

//装维人员管理，只允许post请求
Route::rule([
    //添加装维人员
    'staff/add'  =>  'index/StaffManage/add',
    //查看所有装维人员
    'staff/check' =>  'index/StaffManage/check',
    //查看某个装维人员详情
    'staff/change' =>  'index/StaffManage/change',
    //删除某个装维人员
    'staff/delete' => 'index/StaffManage/delete'
]);

//管理员员管理，只允许post请求
Route::rule([
    'user/add'  =>  'index/UserManage/add',
    'user/check' =>  'index/UserManage/check',
    'user/change' =>  'index/UserManage/change',
    'user/delete' => 'index/UserManage/delete'
]);
