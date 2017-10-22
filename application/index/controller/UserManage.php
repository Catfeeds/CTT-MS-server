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
        //获取json
        $json = $_POST['json'];
        //$json = '[{"username":"007","name":"11","password":"111","confirm_password":"111","area":"四川^雅安^雨城区","storehouse":"雅安主仓","sex":"男","phone":"111","qq":"","email":"","address":"","idcard":"111"},{"stuff_in":"0","stuff_out":"0","stuff_back":"0","stuff_leave":"0","stuff_use":"0","stuff_count":"0","stuff_inventory":"0","tool_in":"0","tool_out":"0","tool_back":"0","tool_leave":"0","tool_count":"0","tool_infoconsummate":"0","safty_in":"0","safty_out":"1","safty_back":"0","safty_count":"0","safty_infoconsummate":"0","staff_manage":"1","user_manage":"0"}]';

        //将json转化为数组
        $data = json_decode($json,true);

        //判断数据格式是否合法
        if(!isset($data[0])||!isset($data[1])) return '{"state":"warning","message":"非法数据格式"}';

        //查询工号和管理员姓名是否已经存在
        $result = db('user')->where('username',$data[0]['username'])->whereOr('name',$data[0]['name'])->select();
        if($result) return '{"state":"warning","message":"员工编号或姓名已存在"}';

        //查询仓库和对应的地区是否合法
        if($data[0]['storehouse']!=''){
            $result0 = db('storehouse')->where('name',$data[0]['storehouse'])->where('area',$data[0]['area'])->select();
            if(!$result0)
                return '{"state":"warning","message":"归属仓库或地址有误"}';
        }

        //使用Manage类的add静态方法验证、添加auth
        $result1 =  Manage::add(new \app\index\model\Auth(),new \app\index\validate\Auth(),$data[1]);
        if($result1['state']!='success')
            return '{"state":"'.$result1['state'].'","message":"管理员权限'.$result1['message'].'"}';

        //使用Manage类的add静态方法验证、添加user
        return json(Manage::add($this->model,$this->validate,$data[0]));
    }

    //查找管理员
    public function check(){
        //有参数的情况
        $query = isset(Request::instance()->post(false)['query'])?Request::instance()->post(false)['query']:null;
        if(!$query)
            return json(['state'=>'warning','message'=>'缺少查询条件']);
        else{
            //示例json
//            $json = '{
//                    "pageinfo":{"curpage":1,"pageinate":2},
//                    "order":"id desc",
//                    "condition":{
//                                "where":[]
//                                }
//                    }';
            $json = $query;
            $array = json_decode($json,true);
            $pageinfo = $array['pageinfo'];
            unset($array['pageinfo']);
            $limit = $array;
            //使用Manage类的check静态方法
            if(empty($limit))
                $staff = Manage::check($this->model,$pageinfo);
            else
                $staff = Manage::check($this->model,$pageinfo,$limit);
        }

        //根据返回数据中的id到Auth表中找到对应的权限数据
        $tmp = $staff;
        if(is_array($tmp[0])) array_shift($tmp);
        $idList = [];
        foreach($tmp as $key=>$user){
            array_push($idList,$user->id);
        }
        $auth = \app\index\model\Auth::all($idList);

        //将staff对象和auth对象分别转换成数组后合并
        $staff =  json_decode(json_encode($staff),true);
        $auth =  json_decode(json_encode($auth),true);
        for($i=0;$i<count($staff);$i++){
            if($i==0) continue;
            unset($staff[$i]['password']);
            unset($staff[$i]['cookie_username']);
            $staff[$i]['auth'] = $auth[$i-1];
        }
        return json($staff);
    }

    //修改管理员信息
    public function change(){
        if(!isset($_POST['json']))
            return json(['state'=>'success','message'=>'没有更新信息']);
        $json = $_POST['json'];
//        $json = '[{"username":"004","name":"徐志雷","area":"湖北^宜昌^主城区","sex":"男","phone":"","qq":"","email":"","address":"","idcard":"1232134234","id":"9"},{"uid":9,"stuff_in":0,"stuff_out":0,"stuff_back":0,
//        "stuff_leave":0,"stuff_use":0,"stuff_count":0,"stuff_inventory":0,"tool_in":0,"tool_out":0,"tool_back":0,"tool_leave":0,"tool_count":0,"tool_infoconsummate":0,"safty_in":0,"safty_out":0,"safty_back":0,"safty_count":0,"safty_infoconsummate":0,"staff_manage":1,"user_manage":1,"area_manage":0}]';
        //将json转化为数组
        $data = json_decode($json,true);

        //查询工号和管理员姓名是否已经存在
        $result = db('user')->where('id','neq',$data[0]['id'])->where('username',$data[0]['username'])->whereOr('name',$data[0]['name'])->select();
        if($result) return '{"state":"warning","message":"员工编号或姓名已存在"}';

        //查询仓库和对应的地区是否合法
        $result0 = db('sotrehouse')->where('name',$data[0]['storehouse'])->where('area',$data[0]['area'])->select();
        if(!$result0)
            return '{"state":"warning","message":"归属仓库或地址有误"}';

        //修改权限
        $result1 = Manage::change(new \app\index\model\Auth(),new \app\index\validate\Auth(),$data[1]);

        //使用Manage类的change静态方法验证、修改数据
        $result2 = Manage::change($this->model,$this->validate,$data[0]);

        if($result1['state']=='warning' &&$result2['state']=='warning')
            return json(['state'=>'warning','message'=>'修改失败，没有任何改动或数据不存在']);
        else
            return json(['state'=>'success','message'=>'修改成功']);
    }

    //删除管理员
    public function delete(){
        $id = input('id');
        //删除Auth表中的数据
        $result = Manage::delete(new \app\index\model\Auth(),$id);
        if($result['state']!='success')
            return '{"state":"'.$result['state'].'","message":"管理员权限'.$result['message'].'"}';
        //删除user表数据
        return json(Manage::delete($this->model,$id));
    }
}