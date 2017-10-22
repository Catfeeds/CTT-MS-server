<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 10:20
 */

namespace app\index\controller;
use think\Request;

class ManufacturerManage extends Base
{
    //检测该用户是否有生产商大类管理权限
    public function __construct()
    {
        parent::__construct();
        //查询$authList中是否有该操作的权限
        if($this->authList->manufacturer_manage == 0){
            die(json_encode(['state'=>'warning','message'=>'没有生产商大类管理权限'],JSON_UNESCAPED_UNICODE));
        }

        //尝试实例化Manufacturer的模型类和验证器类，并且赋值给$model和$validate
        //若这两个类不存在，则抛出异常，返回错误信息
        try {
            $this->model = new \app\index\model\Manufacturer();
            $this->validate = new \app\index\validate\Manufacturer();
        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    //添加生产商
    public function add(){
        $json = $_POST['json'];
        $data = json_decode($json,true);
        //查重
        $result = db('manufacturer')
            ->where('manufacturer',$data['manufacturer'])
            ->find();
        if($result)
            return json(['state'=>'warning','message'=>'该生产商已经存在']);
        //使用Manage类的add静态方法验证、添加数据
        return json(Manage::add($this->model,$this->validate,$data));
    }

    //查找生产商
    public function check(){
        //有参数的情况
        $query = isset(Request::instance()->post(false)['query'])?Request::instance()->post(false)['query']:null;
        if($query){
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

    //修改生产商
    public function change(){
        $json = $_POST['json'];
        $data = json_decode($json,true);
        //查重
        $result = db('manufacturer')
            ->where('id','neq',$data['id'])
            ->where('manufacturer',$data['manufacturer'])
            ->select();
        if(count($result)>0)
            return json(['state'=>'warning','message'=>'该生产商已经存在']);

        //修改其他表中存放的生产商名
        $tableList = [];
        $preManufacturer = db('manufacturer')->where('id',$data['id'])->find();
        foreach ($tableList as $table){
            db($table)
                ->where('manufacturer',$preManufacturer['manufacturer'])
                ->setField('manufacturer',$data['manufacturer']);
        }

        //使用Manage类的change静态方法验证、修改数据
        return json(Manage::change($this->model,$this->validate,$data));
    }

    //删除生产商大类
    public function delete(){
        $id = input('id');
        //查找其他表中是否有该生产商，若有则不能删除
        $manufacturer = db('manufacturer')->where('id',$id)->find();
        $tableList=[];
        foreach ($tableList as $table){
            $res = db($table)->where('manufacturer',$manufacturer['manufacturer'])->find();
            if($res) return json(['state'=>'warning','message'=>'该生产商不能删除，因为在其它表中还存在该生产商']);
        }
        return json(Manage::delete($this->model,$id));
    }
}