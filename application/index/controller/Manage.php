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
     * add方法：添加数据，需要对应的Model类对象和对应的同名Validate类对象进行验证
     * @param Model $model 对应模型类对象
     * @param Validate $validate 对应的验证器类对象
     * @param array $data  数据数组
     * @return array 成功信息或错误信息
     */
    public static function add(Model $model,Validate $validate,array $data){
        //验证数据
        if(!$validate->check($data)){
            return ['state'=>'warning','message'=>$validate->getError()];
        }
        //添加数据
        if($model->data($data)->allowField(true)->save()){
            return ['state'=>'success','message'=>'添加成功'];
        }else{
            return ['state'=>'warning','message'=>'添加失败'];
        }
    }

    /**
     * check方法
     */
    public static function check(Model $model,array $pageinfo=null,array $limit=null){
        //如果传入了当前页数和每页显示数，则返回相应的数据
        if(isset($pageinfo)){
            //如果有限制条件，则限制查询
            if(isset($limit)){
                $order = isset($limit['order'])?$limit['order']:'id';

                //若排序条件为normal，则将$oeder赋值为null，默认顺序
                $con = explode(' ',$order);
                if(isset($con[1]) && $con[1]=='normal') $order='id';

                //计算符合条件的数据总条数
                foreach ($limit['condition'] as $keyword=>$value){
                    if($keyword=='where'){
                        if(isset($value[0])&&isset($value[1]))
                            $model = $model->where($value[0],$value[1]);
                        else  $model = $model->where(1);
                    }
                    else
                        $model = $model->where($value[0],$keyword,$value[1]);
                }
                $dataCount = count($model->select());

                //返回符合条件的对应的排序和分页数据
                foreach ($limit['condition'] as $keyword=>$value){
                    if($keyword=='where')
                        if(isset($value[0])&&$value[1])
                            $model = $model->where($value[0],$value[1]);
                        else  $model = $model->where(1);
                    else
                        $model = $model->where($value[0],$keyword,$value[1]);
                }
                $data = $model->order($order)->page($pageinfo['curpage'],$pageinfo['pageinate'])->select();
            }else{
                //计算符合条件的数据总页数
                $dataCount = count($model->select());
                //没有限制条件的话，直接返回对应页数的数据
                $data = $model->page($pageinfo['curpage'],$pageinfo['pageinate'])->select();
            }
            //计算符合条件的总页数
            array_unshift($data,['datacount'=>$dataCount]);
            return $data;
        }else{
            //否则返回所有数据，分页、详情、条件查询等由js在前端完成
            return $model->where(1)->order('id')->select();
        }
    }

    /**
     * change方法：修改数据，需要对应的Model类对象和对应的同名Validate类对象进行验证
     * @param Model $model 对应模型类对象
     * @param Validate $validate 对应的验证器类对象
     * @param array $data  数据数组
     * @return array 成功信息或错误信息
     */
    public static function change(Model $model,Validate $validate,array $data){
        //验证数据
        if(!$validate->check($data)){
            return ['state'=>'warning','message'=>$validate->getError()];
        }

        //更新数据
        if($model->data($data)->isUpdate(true)->allowField(true)->save()){
            return ['state'=>'success','message'=>'修改成功'];
        }else{
            return ['state'=>'warning','message'=>'修改失败，没有任何改动或数据不存在'];
        }
    }

    /**
     * delete方法：删除数据，需要对应个的Model类对象，可单个删除和批量删除
     * @param Model $model 对应的模型对象
     * @param int|array $id  删除数据的主键id，可以是一个int数字或一个int数组
     * @return array 成功或错误信息
     */
    public static function delete(Model $model,$id){
        if($result = $model::destroy($id)){
            return ['state'=>'success','message'=>'删除成功，共删除'.$result.'条数据'];
        }else{
            return ['state'=>'warning','message'=>'删除失败'];
        }
    }

}