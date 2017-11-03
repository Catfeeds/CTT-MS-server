<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/24
 * Time: 21:55
 */

namespace app\index\controller;
use think\Request;

class Inventory extends Base
{
    //检测该用户是否有材料入库权限
    public function __construct()
    {
        parent::__construct();
        //查询$authList中是否有该操作的权限
        if($this->authList->stuff_count == 0){
            die(json_encode(['state'=>'warning','message'=>'没有材料库存查询管理权限'],JSON_UNESCAPED_UNICODE));
        }
        //尝试实例化Inventory的模型类和验证器类，并且赋值给$model和$validate
        //若这两个类不存在，则抛出异常，返回错误信息
        try {
            $this->model = new \app\index\model\Inventory();
            $this->validate = new \app\index\validate\Inventory();
        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    //查看库存
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
        $result = db('inventory')
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
}