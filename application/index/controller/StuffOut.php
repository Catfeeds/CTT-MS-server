<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/11/17
 * Time: 22:12
 */

namespace app\index\controller;
use think\Db;

class StuffOut extends Base
{
    //检测该用户是否有材料发放权限
    public function __construct()
    {
        parent::__construct();
        //查询$authList中是否有该操作的权限
        if($this->authList->stuff_out == 0){
            die(json_encode(['state'=>'warning','message'=>'没有材料发放权限'],JSON_UNESCAPED_UNICODE));
        }
        //尝试实例化StuffOutRecord的模型类和验证器类，并且赋值给$model和$validate
        //若这两个类不存在，则抛出异常，返回错误信息
        try {
            $this->model = new \app\index\model\StuffOutRecord();
            $this->validate = new \app\index\validate\StuffOutRecord();
        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    //返回管理员审核通过的申请记录
    private function newAplArr(){
        $filed = ['a.*','b.manufacturer','b.type','c.stuff_name','c.unit','c.category_name'];
        $res = db('stuff_out_record')
            ->alias('a')
            ->join('inventory b','a.inventory_id = b.id')
            ->join('stuff c','b.stuff_id = c.id')
            ->field($filed)
            ->where('a.storehouse',$this->user['storehouse'])
            ->where('a.is_out',1)
            ->select();
        return $res;
    }


    //查看管理员审核通过的申请记录条数
    public function newCount(){
        return count($this->newAplArr());
    }


    //查看管理员审核通过的申请记录
    public function newApplication(){
        return json($this->newAplArr());
    }

    //检查提交的操作
    private function checkHandel($id){
        $app = Db::table('stuff_out_record')
            ->where('id'.$id)
            ->find();
        if(empty($app))
            return returnWarning('该申请不存在');
        if($app['storehouse']!==$this->user['storehouse'])
            return returnWarning('仓库不对应，无法操作该申请');
        if($app['is_out']!=1)
            return returnWarning('该申请不是等待材料员审核状态');
        return $app;
    }

    //同意申请
    public function agree($id){
        $res = $this->checkHandel($id);
        if(!is_array($res))
            return $res;
        Db::table('stuff_out_record')
            ->where('id',$id)
            ->update(['is_out'=>4,'operator2'=>$this->user['name']]);
        return returnSuccess('申请已同意');
    }

    //拒绝申请
    public function refuse($id){
        $reason = input('?reason')?input('reason'):null;
        $res = $this->checkHandel($id);
        if(!is_array($res))
            return $res;
        Db::table('stuff_out_record')
            ->where('id',$id)
            ->update(['is_out'=>4,'remark'=>$reason]);
        return returnSuccess('申请已驳回');
    }

    //用于检验存入stuff_out_record表的数据是否正确
    private function checkData($data){
        //检测材料批次在数据库中是否真的存在
        if(!dataIsExist('inventory','id',$data['inventory_id']))
            return returnWarning('该库存材料不存在!');

        //检测这批材料是否可用
        $enabled = db('inventory')->where('id',$data['inventory_id'])->value('enabled');
        if($enabled!=1)
            return returnWarning('该批库存不可用！');

        //检测仓库在数据库中是否真的存在
        if(!dataIsExist('storehouse','name',$data['storehouse']))
            return returnWarning('申请仓库不存在!');

        //检测选择的仓库与装维是否在同一地区
        $storehouseArea = db('storehouse')->where('name',$data['storehouse'])->value('area');
        if($storehouseArea!=$this->user['area'])
            return returnWarning('只能向你所在的地区仓库申请材料');

        return true;
    }

    //修改申请
    public function change(){
        $json = $_POST['json'];
        $data = json_decode($json,true);
        $res = $this->checkHandel($data['id']);
        if(!is_array($res))
            return $res;
        $checkRes = $this->checkData($data);
        if($checkRes!==true) return $checkRes;
        return Manage::change($this->model,$this->validate,$data);
    }

}