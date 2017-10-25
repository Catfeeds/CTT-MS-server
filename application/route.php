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
    'checkauth' =>  ['index/Auth/checkAuth',['method'=>'get|post']],
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

//地区管理
Route::rule([
    'area/add'  =>  'index/AreaManage/add',
    'area/check' =>  'index/AreaManage/check',
    'area/change' =>  'index/AreaManage/change',
    'area/delete' => 'index/AreaManage/delete'
]);

//仓库管理
Route::rule([
    'storehouse/add'  =>  'index/StorehouseManage/add',
    'storehouse/check' =>  'index/StorehouseManage/check',
    'storehouse/change' =>  'index/StorehouseManage/change',
    'storehouse/delete' => 'index/StorehouseManage/delete'
]);

//班组管理
Route::rule([
    'team/add'  =>  'index/TeamManage/add',
    'team/check' =>  'index/TeamManage/check',
    'team/change' =>  'index/TeamManage/change',
    'team/delete' => 'index/TeamManage/delete'
]);


//材料大类管理
Route::rule([
    'category/add'  =>  'index/CategoryManage/add',
    'category/check' =>  'index/CategoryManage/check',
    'category/change' =>  'index/CategoryManage/change',
    'category/delete' => 'index/CategoryManage/delete'
]);

//材料名称管理
Route::rule([
    'stuff/add'  =>  'index/StuffManage/add',
    'stuff/check' =>  'index/StuffManage/check',
    'stuff/change' =>  'index/StuffManage/change',
    'stuff/delete' => 'index/StuffManage/delete'
]);

//生产商管理
Route::rule([
    'manufacturer/add'  =>  'index/ManufacturerManage/add',
    'manufacturer/check' =>  'index/ManufacturerManage/check',
    'manufacturer/change' =>  'index/ManufacturerManage/change',
    'manufacturer/delete' => 'index/ManufacturerManage/delete'
]);

//入库操作
Route::rule([
    'stuffin' => 'index/StuffIn/stuffIn',
]);

//各种选择的查询
Route::rule([
    'areaquery' => 'index/Query/area',    //地区查询
    'storehousequery' => 'index/Query/storehouse',//仓库查询
    'teamquery' => 'index/Query/team',//班组查询
    'categoryquery' => 'index/Query/category',//材料大类查询
    'stuffquery' => 'index/Query/stuff', //材料名称
    'stuffwithidquery' => 'index/Query/stuffWithId', //材料名称和id
]);
