<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 10:20
 */

namespace app\index\controller;
use think\Request;

class Staff extends Base
{
    //检测该用户是否有装维管理权限
    public function __construct()
    {
        parent::__construct();
        //查询$authList中是否有该操作的权限
        if($this->authList->stuff_manage == 0){
            die(json_encode(['state'=>'error','message'=>'没有装维管理权限'],JSON_UNESCAPED_UNICODE));
        }

        //尝试实例化Staff的模型类和验证器类，并且赋值给$model和$validate
        //若这两个类不存在，则抛出异常，返回错误信息
        try {
            $this->model = new \app\index\model\Staff();
            $this->validate = new \app\index\validate\Staff();
        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    //添加装维人员
    public function add(){
        //获取所有的请求变量
//        $data = Request::instance()->param();
//        var_dump($data);
        $json = '{"name":"赵浩云","sex":"男","on_guard":"是","idcard":"510107199611014219","area":"四川-雅安","phone":"18728193218","qq":"640246255","sec_linkman":null,"sec_phone":null,"address":null,"education":null,"school":null,"operator":"Horol","emloyment_date":"2017-10-03","per_pic":"333","idcard_front_pic":"333","idcard_back_pic":"333","remark":null}';
        //将json转化为数组
        $data = json_decode($json,true);
        //使用Manage类的add静态方法验证、添加数据
        return json(Manage::add($this->model,$this->validate,$data));
    }

    //查看所有的装维人员，返回staff表中所有数据，分页，排序，查找，详情等由由js在前端完成
    public function check(){
        //使用Manage类的check静态方法
        $staff = Manage::check($this->model);
        return json($staff);
    }

    //修改装维人员信息
    public function change(){
        $json = '{"id":"3","name":"易铮","sex":"男","on_guard":"是","idcard":"510107199611014219","area":"四川-雅安","phone":"18728193218","qq":"640246255","sec_linkman":null,"sec_phone":null,"address":null,"education":null,"school":null,"operator":"Horol","emloyment_date":"2017-10-03","per_pic":"333","idcard_front_pic":"333","idcard_back_pic":"333","remark":null}';
        //将json转化为数组
        $data = json_decode($json,true);
        //dump($data);
        //使用Manage类的change静态方法验证、修改数据
        return json(Manage::change($this->model,$this->validate,$data));
    }

}