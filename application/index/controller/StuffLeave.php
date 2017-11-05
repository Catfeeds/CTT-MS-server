<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/24
 * Time: 21:55
 */

namespace app\index\controller;
use think\Request;

class StuffLeave extends Base
{
    //检测该用户是否有材料调拨权限
    public function __construct()
    {
        parent::__construct();
        //查询$authList中是否有该操作的权限
        if($this->authList->stuff_leave == 0){
            die(json_encode(['state'=>'warning','message'=>'没有材料入库权限管理权限'],JSON_UNESCAPED_UNICODE));
        }
        //尝试实例化StuffLeaveRecord的模型类和验证器类，并且赋值给$model和$validate
        //若这两个类不存在，则抛出异常，返回错误信息
        try {
            $this->model = new \app\index\model\StuffLeaveRecord();
            $this->validate = new \app\index\validate\StuffLeaveRecord();
        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    //根据stuff_id在inventory表中查询数据
    public function inventoryQuery($stuff_id){
        $inventory = db('inventory')
            ->where('stuff_id',$stuff_id)
            ->where('storehouse',$this->user['storehouse'])
            ->where('enabled','1')
            ->select();
        return json($inventory);
    }

    //新增库存调拨记录
    public function stuffLeave(){
        $json = $_POST['json'];
        $data = json_decode($json,true);

        //检测材料批次在数据库中是否真的存在
        if(!dataIsExist('inventory','id',$data['inventory_id']))
            return returnWarning('该库存材料不存在!');

        //检测这批材料是否可用
        $enabled = db('inventory')->where('id',$data['inventory_id'])->value('enabled');
        if($enabled!=1)
            return returnWarning('该批库存不可用！');

        //检测调拨数量是否大于库存数
        $num = db('inventory')->where('id',$data['inventory_id'])->value('quantity');
        if($num<$data['leave_quantity'])
            return returnWarning('调拨数量大于库存数量！');

        //检测请求仓库在数据库中是否真的存在
        if(!dataIsExist('storehouse','name',$data['send_storehouse']))
            return returnWarning('调离仓库不存在!');

        if(!dataIsExist('storehouse','name',$data['receive_storehouse']))
            return returnWarning('接收仓库仓库不存在!');

        //检测此用户是否管辖该仓库
        $userStorehouse = db('user')->where('cookie_username',$this->cookieUsername)->value('storehouse');
        if($userStorehouse!=$data['send_storehouse'])
            return returnWarning('该管理员无权管理该仓库!');

        //通过cookie来找到当前管理员姓名
        $operator = db('user')->where('cookie_username',$this->cookieUsername)->value('name');
        $data['send_operator'] = $operator;

        //添加记录到StuffLeaveRecord模型
        $res = Manage::add($this->model,$this->validate,$data);
        if($res['state']!='success') return json($res);

        //修改inventory表中的库存数量
        $newNum = $num - $data['leave_quantity'];
        db('inventory')->where('id',$data['inventory_id'])->setField('quantity',$newNum);
        return returnSuccess('调拨成功');
    }


    private function newAplArr(){
        $res = db('stuff_leave_record')
            ->where('receive_storehouse',$this->user['storehouse'])
            ->where('is_received',0)
            ->select();
        return $res;
    }


    //查看尚未处理的材料调拨记录条数
    public function newCount(){
        return count($this->newAplArr());
    }


    //查看尚未处理的材料调拨记录
    public function newApplication(){
        return json($this->newAplArr());
    }


    //接收调拨材料，并且增加入库记录和库存记录
    public function receive($id){
        $stuffLeaveRecord = db('stuff_leave_record')->where('id',$id)->find();
        if(!$stuffLeaveRecord) return returnWarning('该调拨记录不存在');
        if($stuffLeaveRecord['receive_storehouse']!=$this->user['storehouse'])
            return returnWarning('你无权接收该调拨材料，因为你不是接收仓库管理员');
        if($stuffLeaveRecord['is_received']!=0)
            return returnWarning('该材料已被确认接收！');

        $inventory=db('inventory')->where('id',$stuffLeaveRecord['inventory_id'])->find();

        //添加入库记录
        $stuffIndata =[
            'stuff_id'=>$inventory['stuff_id'],
            'manufacturer'=>$inventory['manufacturer'],
            'type'=>$inventory['type'],
            'quantity'=>$stuffLeaveRecord['leave_quantity'],
            'storehouse'=>$stuffLeaveRecord['receive_storehouse'],
            'stuff_in_date'=>date('Y-m-d H:m:s',time()),
            'operator'=>$this->user['name'],
        ];
        $stuffInModel = new \app\index\model\StuffInRecord();
        $res = Manage::add($stuffInModel,new \app\index\validate\StuffInRecord(),$stuffIndata);
        if($res['state']!='success') return json($res);

        //添加库存记录
        $inventorydata=[
            'stuff_in_record_id'=>$stuffInModel->id,
            'stuff_id'=>$inventory['stuff_id'],
            'manufacturer'=>$inventory['manufacturer'],
            'type'=>$inventory['type'],
            'storehouse'=>$stuffLeaveRecord['receive_storehouse'],
            'quantity'=>$stuffLeaveRecord['leave_quantity'],
        ];

        $res = json(Manage::add(new \app\index\model\Inventory(),new \app\index\validate\Inventory(),$inventorydata));
        if($res['state']!='success') return json($res);

        //修改调拨材料记录
        $res = db('stuff_leave_record')
            ->where('id',$id)
            ->update(['receive_operator'=>$this->user['name'],
                      'receive_date'=>date('Y-m-d',time()),
                      'is_received'=>1
                ]);
        if($res) return returnSuccess('材料接收成功');
    }


    //查看调拨记录
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

    //修改调拨记录
    public function change(){
        $json = $_POST['json'];
        $data = json_decode($json,true);

        //查询调拨记录
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

    //取消调拨（删除调拨记录）
    public function cancel(){

    }
}