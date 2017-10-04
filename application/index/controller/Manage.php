<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/2
 * Time: 22:48
 */

namespace app\index\controller;
use Think\Model;
use Think\Validate;

//Manage类，提供添加，修改，查看，详细等的静态方法
class Manage
{
    /**
     * add方法：添加数据，需要对应的Model类和对应的同名Validate类进行验证
     * @param Model $model 对应模型类
     * @param Validate $validate 对应的验证器类
     * @param array $data  数据数组
     * @return string 成功信息或错误信息
     */
    public static function add(Model $model,Validate $validate,array $data){
        //验证数据
        if(!$validate->check($data)){
            return ['error'=>$validate->getError()];
        }
        //添加数据
        $model->data($data)->allowField(true)->save();
        return ['success'=>'添加成功'];
    }

    /**
     * check方法只查询所有数据并返回，分页、详情、条件查询等由js在前端完成
     * @param Model $model 对应Model类
     * @return false|mixed|\PDOStatement|string|\think\Collection 返回查询信息
     */
    public static function check(Model $model){
        return $model->where(1)->select();
    }

    /**
     * change方法：修改数据，需要对应的Model类和对应的同名Validate类进行验证
     * @param Model $model 对应模型类
     * @param Validate $validate 对应的验证器类
     * @param array $data  数据数组
     * @return string 成功信息或错误信息
     */
    public static function change(Model $model,Validate $validate,array $data){
        //验证数据
        if(!$validate->check($data)){
            return ['error'=>$validate->getError()];
        }
        $id = $data['id'];
        unset($data['id']);
        //更新数据
        $model->allowField(true)->save($data,['id'=>$id]);
        return ['success'=>'修改成功'];
    }

}