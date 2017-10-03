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
    //检测该用户是否有装维管理权限
    public function __construct()
    {
        parent::__construct();
        //查询$authList中是否有该操作的权限
        if($this->authList->stuff_manage == 0){
            die(json_encode(['state'=>'error','message'=>'没有装维管理权限'],JSON_UNESCAPED_UNICODE));
        }
        //实例化Staff模型类，并赋值给父类中的$model
        $this->model = new \app\index\model\Staff();
    }

    //添加装维人员
    public function add(){
        //获取所有的请求变量
//        $data = Request::instance()->param();
//        var_dump($data);

        $data = [];
        //使用Manage类的add静态方法验证、添加数据
        var_dump(Manage::add($this->model,$data));
    }

    //查看所有的装维人员，返回staff表中所有数据，分页，排序，查找，详情等由由js在前端完成
    public function check(){
        //使用Manage类的check静态方法
        $staff = Manage::check($this->model);
        return json($staff);
    }

    //修改装维人员信息
    public function change(){

    }

}