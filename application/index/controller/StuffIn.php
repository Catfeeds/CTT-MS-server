<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/24
 * Time: 21:55
 */

namespace app\index\controller;


use Illuminate\Database\Capsule\Manager;

class StuffIn extends Base
{
    //检测该用户是否有材料入库权限
    public function __construct()
    {
        parent::__construct();
        //查询$authList中是否有该操作的权限
        if($this->authList->stuff_in == 0){
            die(json_encode(['state'=>'warning','message'=>'没有材料入库权限管理权限'],JSON_UNESCAPED_UNICODE));
        }
        //尝试实例化StuffInRecord的模型类和验证器类，并且赋值给$model和$validate
        //若这两个类不存在，则抛出异常，返回错误信息
        try {
            $this->model = new \app\index\model\StuffInRecord();
            $this->validate = new \app\index\validate\StuffInRecord();
        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    //新增入库记录和库存
    public function stuffIn(){
        $json = input('json');
        $data = json_decode($json,true);
        //$data = ['stuff_id'=>1,'manufacturer'=>'咪咕','type'=>'小猫','quantity'=>200,'storehouse'=>'丹棱库','stuff_in_date'=>'2017-10-25 21:20'];

        //检测请求数据在数据库中是否真的存在
        if(!dataIsExist('manufacturer','manufacturer',$data['manufacturer']))
            return returnWarning('该生产商不存在!');
        if(!dataIsExist('storehouse','name',$data['storehouse']))
            return returnWarning('该仓库不存在!');

        //检测此用户是否管辖该仓库
        $userStorehouse = db('user')->where('cookie_username',$this->cookieUsername)->value('storehouse');
        if($userStorehouse!=$data['storehouse'])
            return returnWarning('该管理员无权管理该仓库!');

        //通过cookie来找到当前管理员姓名
        $operator = db('user')->where('cookie_username',$this->cookieUsername)->value('name');
        $data['operator'] = $operator;

        //添加记录到StuffInRecord模型
        $res = Manage::add($this->model,$this->validate,$data);
        if($res['state']!='success') return json($res);

        //添加记录到Inventory模型
        $data['stuff_in_record_id'] = $this->model->id;
        return json(Manage::add(new \app\index\model\Inventory(),new \app\index\validate\Inventory(),$data));
    }

    //修改入库记录
    public function change(){
        $json = input('json');
        $data = json_decode($json,true);

        //检查是否在一小时之内
        if(time()-strtotime($data['stuff_in_date'])>3600)
            return returnWarning('已经超出可修改时间范围');
        unset($data['stuff_in_date']);

        //检查是否是本管理员
        $opertor = db('stuff_in_record')->where('id',$data['id'])->value('operator');
        $userName = getUser()['name'];
        if(!($opertor==$userName && $userName==$data['operator']))
            return returnWarning('你不是该入库材料经办人，无权修改');


        return json(Manage::change($this->model,$this->validate,$data));

    }
}