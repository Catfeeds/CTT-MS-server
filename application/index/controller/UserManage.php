<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 10:20
 */

namespace app\index\controller;
use think\Request;

class UserManage extends Base
{
    //检测该用户是否有管理员管理管理权限
    public function __construct()
    {
        parent::__construct();
        //查询$authList中是否有该操作的权限
        if($this->authList->user_manage == 0){
            die(json_encode(['state'=>'warning','message'=>'没有管理员管理权限'],JSON_UNESCAPED_UNICODE));
        }

        //尝试实例化User的模型类和验证器类，并且赋值给$model和$validate
        //若这两个类不存在，则抛出异常，返回错误信息
        try {
            $this->model = new \app\index\model\User();
            $this->validate = new \app\index\validate\User();
        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    //添管理员
    public function add(){
        //获取所有的请求变量
//        $data = Request::instance()->param();
        $json = '[{"username":"002","password":"10470c3b4b1fed12c3baac014be15fac67c6e815","area":"雅安","name":"李四","sex":"男","phone":"13608178123","qq":null,"email":null,"address":null,"idcard":"510107199711014217"},
        {"stuff_in":0,"stuff_out":0,"stuff_back":0,"stuff_leave":0,"stuff_use":0,"stuff_count":0,"stuff_inventory":0,"tool_in":0,"tool_out":0,"tool_back":0,"tool_leave":0,"tool_count":0,"tool_infoconsummate":0,"safty_in":0,"safty_out":0,"safty_back":0,"safty_count":0,"safty_infoconsummate":0,"staff_manage":1,"user_manage":1}]';

        //将json转化为数组
        $data = json_decode($json,true);

        //判断数据格式是否合法
        if(!isset($data[0])||!isset($data[1])) return '{"state":"warning","message":"非法数据格式"}';

        //查询工号和管理员姓名是否已经存在
        $result = db('user')->where('username',$data[0]['username'])->whereOr('name',$data[0]['name'])->select();
        if($result) return '{"state":"warning","message":"员工编号或姓名已存在"}';

        //使用Manage类的add静态方法验证、添加auth
        $result1 =  Manage::add(new \app\index\model\Auth(),new \app\index\validate\Auth(),$data[1]);
        if($result1['state']!='success')
            return '{"state":"'.$result1['state'].'","message":"管理员权限'.$result1['message'].'"}';

        //使用Manage类的add静态方法验证、添加user
        return json(Manage::add($this->model,$this->validate,$data[0]));
    }

    //查找管理员
    public function check(){
        //示例json
//        $json = '{
//                    "pageinfo":{"curpage":2,"pageinate":3},
//                    "order":"id desc",
//                    "condition":{
//                                "where":["on_guard","是"],
//                                "like":["area|name","%四川%"],
//                                "between":["employment_date",["2017-10-01","2017-10-08"]]
//                                }
//                    }';
//        $array = json_decode($json,true);
//        $pageinfo = $array['pageinfo'];
//        unset($array['pageinfo']);
//        $limit = $array;
        //使用Manage类的check静态方法
        $staff = Manage::check($this->model);
        //dump($staff);
        //$auth = Manage::check(new \app\index\model\Auth());
        $auth = [];
//        foreach ($staff as $iterm){
//           array_push($auth,$iterm['id']);
//            dump($iterm);
//        }
        return json($staff);
    }

    //修改管理员信息
    public function change(){
        $json = '{"id":"1","username":"001","password":"10470c3b4b1fed12c3baac014be15fac67c6e815","area":"雅安","name":"李四","sex":"男","phone":"13608178123","qq":null,"email":null,"address":null,"idcard":"510107199711014217","cookie_username":"086cbfb4d2e91aeac1a16aa66162e85d","last_login_time":"2017-10-06 10:39:53","last_logout_time":"2017-10-06 10:39:53"}';
        //将json转化为数组
        $data = json_decode($json,true);

        //使用Manage类的change静态方法验证、修改数据
        return json(Manage::change($this->model,$this->validate,$data));
    }

    //删除管理员
    public function delete(){
        $id = range(2,11);
        //删除Auth表中的数据
        $result = Manage::delete(new \app\index\model\Auth(),$id);
        if($result['state']!='success')
            return '{"state":"'.$result['state'].'","message":"管理员权限'.$result['message'].'"}';
        //删除user表数据
        return json(Manage::delete($this->model,$id));
    }
}