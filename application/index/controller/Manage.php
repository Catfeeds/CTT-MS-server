<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/2
 * Time: 22:48
 */

namespace app\index\controller;
use Think\Model;

//Manage类，提供添加，修改，查看，详细等的静态方法
class Manage
{
    /**
     * add方法：添加数据，需要对应的Model类和对应的同名Validate类进行验证
     * @param Model $model 对应Model类
     * @param array $data  数据数组
     * @return string 成功信息或错误信息
     */
    public static function add(Model $model,array $data){
        //model添加数据
        $result = $model->validate(true)->save($data);
        if(false === $result){
            // 验证失败 输出错误信息
            return $model->getError();
        }
        return 'success';
    }

    /**
     * check方法只查询所有数据并返回，分页、详情、条件查询等由js在前端完成
     * @param Model $model 对应Model类
     * @return false|mixed|\PDOStatement|string|\think\Collection 返回查询信息
     */
    public static function check(Model $model){
        return $model->where(1)->select();
    }

    public static function change($var){
        return  $var;
    }

}