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
            die(json_encode(['state'=>'warning','message'=>'没有材料发放审批权限'],JSON_UNESCAPED_UNICODE));
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

    //返回未处理申请记录
    private function newAplArr(){
        $filed = ['a.*','b.manufacturer','b.type','storehouse','c.stuff_name','c.unit','c.category_name'];
        $res = db('stuff_out_record')
            ->alias('a')
            ->join('inventory b','a.inventory_id = b.id')
            ->join('stuff c','b.stuff_id = c.id')
            ->field($filed)
            ->where('storehouse',$this->user['storehouse'])
            ->where('is_out',0)
            ->select();
        return $res;
    }


    //查看尚未处理的材料申请记录条数
    public function newCount(){
        return count($this->newAplArr());
    }


    //查看尚未处理的材料申请记录
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
        return $app;
    }

    //同意申请
    public function agree($id){
        $res = $this->checkHandel($id);
        if(!is_null(json_decode($res)))
            return $res;
        $app = $res;
        if($app['is_out']!=0)
            return returnWarning('该申请不是待审核状态');
        Db::table('stuff_out_app')
            ->where('id',$id)
            ->setField('is_out',1);
        return returnSuccess('申请已同意');
    }

    //拒绝申请
    public function refuse($id){
        $reason = input('?reason')?input('reason'):null;
        $res = $this->checkHandel($id);
        if(!is_null(json_decode($res)))
            return $res;
        $app = $res;
        if($app['is_out']!=0)
            return returnWarning('该申请不是待审核状态');
        Db::table('stuff_out_app')
            ->where('id',$id)
            ->update(['is_out'=>2,'remark'=>$reason]);
        return returnSuccess('申请已驳回');
    }

}