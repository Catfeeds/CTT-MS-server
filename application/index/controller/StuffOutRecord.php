<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/11/21
 * Time: 9:53
 */

namespace app\index\controller;
use think\Request;
use think\Db;

class StuffOutRecord extends Base
{
    //检测该用户是否有材料发放查看权限
    public function __construct()
    {
        parent::__construct();
        //查询$authList中是否有该操作的权限
        if($this->authList->stuff_review == 0 && $this->authList->stuff_out == 0){
            die(json_encode(['state'=>'warning','message'=>'没有查看材料发放记录权限'],JSON_UNESCAPED_UNICODE));
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

    //查询已经发放的材料记录（已经接收）
    public function check(){
        $json = isset(Request::instance()->post(false)['query'])?Request::instance()->post(false)['query']:null;
        //$json = '{"pageinfo":{"curpage":1,"pageinate":10},"condition":{"where":["a.storehouse","丹棱一库"],"like":["manufacturer|a.storehouse|category_name|stuff_name|staff|operator1|operator2","%网%"]}}';
        if(empty($json)) return returnWarning("缺少查询json");
        $array = json_decode($json,true);
        $pageinfo = $array['pageinfo'];
        unset($array['pageinfo']);
        $limit = $array;
        $userStorehouse = $this->user['storehouse'];
        //查询登录用户所在的仓库的入库记录
        $filed = ['a.*','b.manufacturer','b.type','b.stuff_id','c.stuff_name','c.unit','c.category_name'];
        $result = db('stuff_out_record')
            ->alias('a')
            ->join('inventory b','a.inventory_id = b.id')
            ->join('stuff c','b.stuff_id = c.id')
            ->field($filed)
            ->where('a.is_out',5)
            ->where('a.storehouse',$userStorehouse);
        $result1 = db('stuff_out_record')
            ->alias('a')
            ->join('inventory b','a.inventory_id = b.id')
            ->join('stuff c','b.stuff_id = c.id')
            ->field($filed)
            ->where('a.is_out',5)
            ->where('a.storehouse',$userStorehouse);
        $order = isset($limit['order'])?$limit['order']:'a.out_date desc';
        //若排序条件为normal，则将$oeder赋值为null，时间逆序
        $con = explode(' ',$order);
        if(isset($con[1]) && $con[1]=='normal') $order='a.out_date desc';
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

    //查询已经发放的材料记录（已审批接收）
    public function check2(){
        $json = isset(Request::instance()->post(false)['query'])?Request::instance()->post(false)['query']:null;
        //$json = '{"pageinfo":{"curpage":1,"pageinate":10},"condition":{"where":["a.storehouse","丹棱一库"],"like":["manufacturer|a.storehouse|category_name|stuff_name|staff|operator1|operator2","%网%"]}}';
        if(empty($json)) return returnWarning("缺少查询json");
        $array = json_decode($json,true);
        $pageinfo = $array['pageinfo'];
        unset($array['pageinfo']);
        $limit = $array;
        $userStorehouse = $this->user['storehouse'];
        //查询登录用户所在的仓库的入库记录
        $filed = ['a.*','b.manufacturer','b.type','b.stuff_id','c.stuff_name','c.unit','c.category_name'];
        $result = db('stuff_out_record')
            ->alias('a')
            ->join('inventory b','a.inventory_id = b.id')
            ->join('stuff c','b.stuff_id = c.id')
            ->field($filed)
            ->where('a.is_out',1)
            ->where('a.storehouse',$userStorehouse);
        $result1 = db('stuff_out_record')
            ->alias('a')
            ->join('inventory b','a.inventory_id = b.id')
            ->join('stuff c','b.stuff_id = c.id')
            ->field($filed)
            ->where('a.is_out',1)
            ->where('a.storehouse',$userStorehouse);
        $order = isset($limit['order'])?$limit['order']:'a.out_date desc';
        //若排序条件为normal，则将$oeder赋值为null，时间逆序
        $con = explode(' ',$order);
        if(isset($con[1]) && $con[1]=='normal') $order='a.out_date desc';
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

}