<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 10:20
 */

namespace app\index\controller;
use think\Request;

class AreaManage extends Base
{
    //检测该用户是否有地区管理权限
    public function __construct()
    {
        parent::__construct();
        //查询$authList中是否有该操作的权限
        if($this->authList->area_manage == 0){
            die(json_encode(['state'=>'warning','message'=>'没有片区管理权限'],JSON_UNESCAPED_UNICODE));
        }

        //尝试实例化Area的模型类和验证器类，并且赋值给$model和$validate
        //若这两个类不存在，则抛出异常，返回错误信息
        try {
            $this->model = new \app\index\model\Area();
            $this->validate = new \app\index\validate\Area();
        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    //添加片区
    public function add(){
        $json = $_POST['json'];
        $data = json_decode($json,true);
        //查重
        $result = db('area')
            ->where('province',$data['province'])
            ->where('city',$data['city'])
            ->where('district',$data['district'])
            ->find();
        if($result)
            return json(['state'=>'warning','message'=>'该片区已经存在']);
        //使用Manage类的add静态方法验证、添加数据
        return json(Manage::add($this->model,$this->validate,$data));
    }

    //查找的地区
    public function check(){
        //有参数的情况
        $query = isset(Request::instance()->post(false)['query'])?Request::instance()->post(false)['query']:null;
        if($query){
            //示例json
            //$json = '{"pageinfo":{"curpage":1,"pageinate":10},"order":"province desc"}';
            $json = $query;
            $array = json_decode($json,true);
            $pageinfo = $array['pageinfo'];
            unset($array['pageinfo']);
            $limit = $array;
            //使用Manage类的check静态方法
            if(empty($limit))
                $staff = Manage::check($this->model,$pageinfo);
            else
                $staff = Manage::check($this->model,$pageinfo,$limit);
        }
        else  //没有参数的情况
            $staff = Manage::check($this->model);
        return json($staff);
    }

    //修改地区信息
    public function change(){
        $json = $_POST['json'];
        $data = json_decode($json,true);
        //查重
        $result = db('area')
            ->where('province',$data['province'])
            ->where('city',$data['city'])
            ->where('district',$data['district'])
            ->find();
        if($result)
            return json(['state'=>'warning','message'=>'该片区已经存在']);

        //修改其他表中area字段的值
        $newArea = $data['province'].'^'.$data['city'].'^'.$data['district'];
        $res = Manage::changeArea($data['id'],$newArea,['staff','user','team','storehouse']);
        if(true!==$res) return json($res);

        //使用Manage类的change静态方法验证、修改数据
        return json(Manage::change($this->model,$this->validate,$data));
    }

    //删除地区
    public function delete(){
        $id = input('id');
        //检测其他表中是否还有该地区存在，若存在，则不能删除
        $area = db('area')->where('id',$id)->find();
        $areStr = $area['province'].'^'.$area['city'];
        $tableList=['staff','user','team','storehouse'];
        foreach ($tableList as $table){
            $res = db($table)->where('area','like','%'.$areStr.'%')->find();
            if($res) return json(['state'=>'warning','message'=>'该地区不能删除，因为在其它表中还存在该地区']);
        }

        return json(Manage::delete($this->model,$id));
    }
}