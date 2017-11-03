<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/24
 * Time: 21:55
 */

namespace app\index\controller;
use think\Request;

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
        $json = $_POST['json'];
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
        //$json = '{"id":10,"stuff_id":2,"manufacturer":"烽火","type":"12","quantity":44,"storehouse":"丹棱库","stuff_in_date":"2017-11-03 13:32:41","remark":"432"}';
        $json = $_POST['json'];
        $data = json_decode($json,true);

        //查询入库记录
        $stuffInRecord = db('stuff_in_record')->where('id',$data['id'])->find();
        $dateTime = $stuffInRecord['stuff_in_date'];
        $opertor = $stuffInRecord['operator'];

        //检查是否在一小时之内
        if((time()-strtotime($dateTime))>3600)
            return returnWarning('已经超出可修改时间范围');

        //检查是否是本管理员
        $userName = getUser()['name'];
        if(!($opertor==$userName))
            return returnWarning('你不是该入库材料经办人，无权修改');

        $data['stuff_in_date'] = $dateTime;
        $data['operator'] = $opertor;

        //修改stuff_in_record表
        $res = Manage::change($this->model,$this->validate,$data);
        if($res['state']!='success') return json($res);

        $data['stuff_in_record_id'] = $data['id'];
        unset($data['stuff_in_date']);
        unset($data['remark']);
        unset($data['operator']);
        unset($data['id']);

        //修改inventory表
        db('inventory')
            ->where('stuff_in_record_id',$data['stuff_in_record_id'])
            ->update($data);
        return returnSuccess('修改成功');
    }

    //查看入库记录
    public function check(){
        $json = isset(Request::instance()->post(false)['query'])?Request::instance()->post(false)['query']:null;
        //$json ='{"pageinfo":{"curpage":1,"pageinate":3},"order":"a.id desc","condition":{"like":["manufacturer","%咪咕%"],"between":["stuff_in_date",["2017-10-01","2017-10-30"]]}}';
        //$json = '{"pageinfo":{"curpage":1,"pageinate":10},"condition":{"where":["a.id","1"]}}';
        if(empty($json)) return returnWarning("缺少查询json");
        $array = json_decode($json,true);
        $pageinfo = $array['pageinfo'];
        unset($array['pageinfo']);
        $limit = $array;
        $userStorehouse = getUser()['storehouse'];
        //查询登录用户所在的仓库的入库记录
        $filed = ['a.*','b.stuff_name','b.unit','b.category_name'];
        $result = db('stuff_in_record')
            ->alias('a')
            ->join('stuff b','a.stuff_id = b.id')
            ->field($filed)
            ->where('storehouse',$userStorehouse);
        $result1 = db('stuff_in_record')
            ->alias('a')
            ->join('stuff b','a.stuff_id = b.id')
            ->field($filed)
            ->where('storehouse',$userStorehouse);
        $order = isset($limit['order'])?$limit['order']:'a.id';
        //若排序条件为normal，则将$oeder赋值为null，默认顺序
        $con = explode(' ',$order);
        if(isset($con[1]) && $con[1]=='normal') $order='a.id';
        foreach ($limit['condition'] as $keyword=>$value){
            if($keyword=='where'){
                if(isset($value[0])&&isset($value[1])){
                    $result = $result->where($value[0],$value[1]);
                    $result1 = $result1->where($value[0],$value[1]);
                }
                else{
                    $result = $result->where(1);
                    $result1 = $result1->where(1);
                }
            }
            else{
                $result = $result->where($value[0],$keyword,$value[1]);
                $result1 = $result1->where($value[0],$keyword,$value[1]);
            }
        }
        $result = $result ->order($order)->page($pageinfo['curpage'],$pageinfo['pageinate'])->select();
        $dataCount = count($result1->select());
        array_unshift($result,['datacount'=>$dataCount]);
        return json($result);
    }

    //修改材料是否可用
    public function changeStuffEnabled($id){
        //根据id找到入库记录表中的仓库
        $stuffInRecord = db('stuff_in_record')->where('id',$id)->find();
        if(empty($stuffInRecord))
            return returnWarning('该入库记录不存在');
        if($this->user['storehouse']!=$stuffInRecord['storehouse'])
            return returnWarning('该管理员无权管理该仓库!');

        $newEnabled = $stuffInRecord['enabled']==0?1:0;
        $data = ['enabled'=>$newEnabled];

        //修改stuff_in_record表
        db('stuff_in_record')->where('id',$id)->setField($data);

        //修改inventory表
       db('inventory')->where('stuff_in_record_id',$id)->setField($data);

       return returnSuccess($newEnabled);
    }
}