<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 10:20
 */

namespace app\index\controller;
use think\Request;

class Staff extends Base
{
    //检测该用户是否有操作权限
    public function __construct()
    {
        parent::__construct();
        //查询$authList中是否有该操作的权限
        if($this->authList->stuff_manage == 0){
            die(json_encode(['state'=>'error','message'=>'没有装维管理权限'],JSON_UNESCAPED_UNICODE));
        }
    }

    //添加装维人员
    public function add(){
        //获取所有的请求变量
        $data = Request::instance()->param();

        //验证、储存提交的信息
        $result = $this->validate($data,'Staff');
        if(true!==$result){
            return json(['state'=>'error','message'=>$result]);
        }else{
            return json(['state'=>'success','message'=>'装维人员添加成功']);
        }
    }
}