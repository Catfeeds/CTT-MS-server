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

//login页面
Route::post('login','index/Auth/login');

//logout页面
Route::rule('logout','index/Auth/logout');

//权限认证，只允许post方式请求
Route::rule('checkauth','index/Auth/checkAuth');

//添加装维人员，只允许post请求
Route::rule('staff/add','index/Staff/add');