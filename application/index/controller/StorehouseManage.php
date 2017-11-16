<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 10:20
 */

namespace app\index\controller;
use think\Request;

class StorehouseManage extends Base
{
    //检测该用户是否有仓库管理权限
    public function __construct()
    {
        parent::__construct();
        //查询$authList中是否有该操作的权限
        if($this->authList->storehouse_manage == 0){
            die(json_encode(['state'=>'warning','message'=>'没有仓库管理权限'],JSON_UNESCAPED_UNICODE));
        }

        //尝试实例化Storehouse的模型类和验证器类，并且赋值给$model和$validate
        //若这两个类不存在，则抛出异常，返回错误信息
        try {
            $this->model = new \app\index\model\Storehouse();
            $this->validate = new \app\index\validate\Storehouse();
        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    //添加仓库
    public function add(){
        $json = $_POST['json'];
        $data = json_decode($json,true);

        //查看地区是否真实存在
        if(!Manage::checkArea($data['area']))
            return json(['state'=>'warning','message'=>'地区不存在']);

        //查重
        $result = db('storehouse')
            ->where('name',$data['name'])
            ->find();
        if($result)
            return json(['state'=>'warning','message'=>'该仓库已经存在']);

        //使用Manage类的add静态方法验证、添加数据
        return json(Manage::add($this->model,$this->validate,$data));
    }

    //查找仓库
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

    //修改仓库信息
    public function change(){
        $json = $_POST['json'];
        $data = json_decode($json,true);

        //查看地区是否真实存在
        if(!Manage::checkArea($data['area']))
            return json(['state'=>'warning','message'=>'地区不存在']);

        //查重
        $result = db('storehouse')
            ->where('id','neq',$data['id'])
            ->where('name',$data['name'])
            ->find();
        if($result)
            return json(['state'=>'warning','message'=>'该仓库已经存在']);

        //修改其他表中存放的仓库名
        $tableList = ['user','inventory','stuff_in_record','stuff_out_record'];
        $preStorehouse = db('storehouse')->where('id',$data['id'])->find();
        foreach ($tableList as $table){
            db($table)->where('storehouse',$preStorehouse['name'])->setField('storehouse',$data['name']);
        }
        db('stuff_leave_record')->where('send_storehouse',$preStorehouse['name'])->setField('send_storehouse',$data['name']);
        db('stuff_leave_record')->where('receive_storehouse',$preStorehouse['name'])->setField('receive_storehouse',$data['name']);

        //修改user表中的地址
        db('user')->where('storehouse',$preStorehouse['name'])->setField('area',$data['area']);

        //使用Manage类的change静态方法验证、修改数据
        return json(Manage::change($this->model,$this->validate,$data));
    }

    //删除仓库
    public function delete(){
        $id = input('id');
        //查找其他表中是否有该仓库，若有则不能删除
        $storehouse = db('storehouse')->where('id',$id)->find();
        $tableList = ['user','inventory','stuff_in_record','stuff_out_record'];
        foreach ($tableList as $table){
            $res = db($table)->where('storehouse',$storehouse['name'])
                ->find();
            if($res) return json(['state'=>'warning','message'=>'该仓库不能删除，因为在其它表中还存在该仓库']);
        }
        $res = db('stuff_leave_record')->where('send_storehouse',$storehouse['name'])->whereor('receive_storehouse',$storehouse['name'])->find();
        if($res) return json(['state'=>'warning','message'=>'该仓库不能删除，因为在其它表中还存在该仓库']);
        return json(Manage::delete($this->model,$id));
    }
}