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
            die(json_encode(['state'=>'warning','message'=>'没有材料调拨权限管理权限'],JSON_UNESCAPED_UNICODE));
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

    //用于检验存入stuff_leave_record表的数据是否正确
    private function checkData($data){
        //检测材料批次在数据库中是否真的存在
        if(!dataIsExist('inventory','id',$data['inventory_id']))
            return returnWarning('该库存材料不存在!');

        //检测这批材料是否可用
        $enabled = db('inventory')->where('id',$data['inventory_id'])->value('enabled');
        if($enabled!=1)
            return returnWarning('该批库存不可用！');


        //检测请求仓库在数据库中是否真的存在
        if(!dataIsExist('storehouse','name',$data['send_storehouse']))
            return returnWarning('调离仓库不存在!');

        if(!dataIsExist('storehouse','name',$data['receive_storehouse']))
            return returnWarning('接收仓库仓库不存在!');

        //检测此用户是否管辖该仓库
        $userStorehouse = db('user')->where('cookie_username',$this->cookieUsername)->value('storehouse');
        if($userStorehouse!=$data['send_storehouse'])
            return returnWarning('该管理员无权管理该仓库!');

        return true;
    }

    //新增库存调拨记录
    public function stuffLeave(){
        $json = $_POST['json'];
        $data = json_decode($json,true);

        $checkRes = $this->checkData($data);
        if($checkRes!==true) return $checkRes;

        //检测调拨数量是否大于库存数
        $num = db('inventory')->where('id',$data['inventory_id'])->value('quantity');
        if($num<$data['leave_quantity'])
            return returnWarning('调拨数量大于库存数量！');

        //通过cookie来找到当前管理员姓名
        $operator = db('user')->where('cookie_username',$this->cookieUsername)->value('name');
        $data['send_operator'] = $operator;

        //添加记录到StuffLeaveRecord模型
        $res = Manage::add($this->model,$this->validate,$data);
        if($res['state']!='success') return json($res);

        //修改inventory表中的库存数量
        $newNum = $num - $data['leave_quantity'];
        db('inventory')->where('id',$data['inventory_id'])->setField('quantity',$newNum);
        return returnSuccess('调拨申请成功');
    }


    //返回未处理调拨记录
    private function newAplArr(){
        $filed = ['a.*','b.manufacturer','b.type','storehouse','c.stuff_name','c.unit','c.category_name'];
        $res = db('stuff_leave_record')
                ->alias('a')
                ->join('inventory b','a.inventory_id = b.id')
                ->join('stuff c','b.stuff_id = c.id')
                ->field($filed)
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

        $res = Manage::add(new \app\index\model\Inventory(),new \app\index\validate\Inventory(),$inventorydata);
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

    //修改调拨记录
    public function change(){
        $json = $_POST['json'];
        //$json = '{"id":1,"inventory_id":1,"send_storehouse":"丹棱一库","receive_storehouse":"丹棱一库","leave_quantity":50,"send_date":"2017-11-15"}';
        $data = json_decode($json,true);

        $checkRes = $this->checkData($data);
        if($checkRes!==true) return $checkRes;

        //检测调拨记录
        $stuff_leave_record = db('stuff_leave_record')->where('id',$data['id'])->find();
        if(empty($stuff_leave_record)) return returnWarning('该调拨记录不存在');
        if($stuff_leave_record['is_received']!=0)
            return returnWarning('该调拨已被接收，不能再修改');
        if($stuff_leave_record['send_storehouse']!=$this->user['storehouse'])
            return returnWarning('无权修改该仓库的调拨记录');
        if($stuff_leave_record['send_operator']!=$this->user['name'])
            return returnWarning('只有经办人本人才能修改调拨记录');

        //检测调拨数量是否大于库存数
        $num = db('inventory')->where('id',$data['inventory_id'])->value('quantity');
        $num+=$stuff_leave_record['leave_quantity'];
        if($num<$data['leave_quantity'])
            return returnWarning('调拨数量大于库存数量！');

        $data['send_operator'] = $this->user['name'];

        //修改记录StuffLeaveRecord模型
        $res = Manage::change($this->model,$this->validate,$data);
        if($res['state']!='success') return json($res);

        //修改inventory表中的库存数量
        $newNum = $num - $data['leave_quantity'];
        db('inventory')->where('id',$data['inventory_id'])->setField('quantity',$newNum);
        return returnSuccess('修改调拨申请成功');
    }

    //查看调拨记录
    public function check(){
        $json = isset(Request::instance()->post(false)['query'])?Request::instance()->post(false)['query']:null;
        if(empty($json)) return returnWarning("缺少查询json");
        $array = json_decode($json,true);
        $pageinfo = $array['pageinfo'];
        unset($array['pageinfo']);
        $limit = $array;
        $userStorehouse = getUser()['storehouse'];
        //查询登录用户所在的仓库的入库记录
        $filed = ['a.*','b.manufacturer','b.type','b.stuff_id','storehouse','c.stuff_name','c.unit','c.category_name'];
        $result = db('stuff_leave_record')
            ->alias('a')
            ->join('inventory b','a.inventory_id = b.id')
            ->join('stuff c','b.stuff_id = c.id')
            ->field($filed)
            ->where('storehouse',$userStorehouse);
        $result1 = db('stuff_leave_record')
            ->alias('a')
            ->join('inventory b','a.inventory_id = b.id')
            ->join('stuff c','b.stuff_id = c.id')
            ->field($filed)
            ->where('storehouse',$userStorehouse);
        $order = isset($limit['order'])?$limit['order']:'a.send_date desc';
        //若排序条件为normal，则将$oeder赋值为null，时间逆序
        $con = explode(' ',$order);
        if(isset($con[1]) && $con[1]=='normal') $order='a.send_date desc';
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

    //取消调拨（删除调拨记录）
    public function cancel($id){
        $stuff_leave_record = db('stuff_leave_record')->where('id',$id)->find();
        if(empty($stuff_leave_record))
            return returnWarning('调拨记录不存在');
        if($stuff_leave_record['send_operator']!=$this->user['name'])
            return returnWarning('你不是该申请经办人，无权取消');
        if($stuff_leave_record['is_received']!=0)
            return returnWarning('该批材料调拨已被接收，无法取消');

        //在对应库存表中将调拨走的材料加回去
        db('inventory')
            ->where('id',$stuff_leave_record['inventory_id'])
            ->setInc('quantity',$stuff_leave_record['leave_quantity']);

        //删除材料调拨记录
        return json(Manage::delete($this->model,$id));
    }
}