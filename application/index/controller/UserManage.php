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
        if(!empty($data[0]['storehouse'])){
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

        //$json = '[{"username":"001","name":"超管1","area":"四川^眉山^丹棱","storehouse":"丹棱库","sex":"男","phone":null,"qq":null,"email":null,"address":null,"idcard":"00001","id":"1"},{"uid":1,"stuff_in":1,"stuff_out":1,"stuff_leave":1,"stuff_use":1,"stuff_count":1,"stuff_inventory":1,"tool_in":1,"tool_out":1,"tool_back":1,"tool_leave":1,"tool_count":1,"tool_infoconsummate":1,"safty_in":1,"safty_out":1,"safty_back":1,"safty_count":1,"safty_infoconsummate":1,"staff_manage":1,"user_manage":1,"area_manage":1,"storehouse_manage":1,"team_manage":1,"category_manage":1,"stuff_manage":1,"manufacturer_manage":1}]';

        //将json转化为数组
        $data = json_decode($json,true);


        //查询工号和管理员姓名是否已经存在
        $result = db('user')->where('id','neq',$data[0]['id'])->where('username',$data[0]['username'])->select();
        if($result)
            return '{"state":"warning","message":"员工编号或姓名已存在"}';

        //查询仓库和对应的地区是否合法
        if(!empty($data[0]['storehouse'])){
            $result0 = db('storehouse')->where('name',$data[0]['storehouse'])->where('area',$data[0]['area'])->find();
            if(!$result0)
                return '{"state":"warning","message":"归属仓库或地址有误"}';
        }

        //修改其他表中存放的管理员姓名
        $tableList = ['stuff_in_record','staff'];
        $preUser = db('user')->where('id',$data[0]['id'])->find();
        foreach ($tableList as $table){
            db($table)
                ->where('operator',$preUser['name'])
                ->setField('operator',$data[0]['name']);
        }
        db('stuff_leave_record')
            ->where('send_operator',$preUser['name'])
            ->setField('send_operator',$data[0]['name']);
        db('stuff_leave_record')
            ->where('receive_operator',$preUser['name'])
            ->setField('receive_operator',$data[0]['name']);
        db('stuff_out_record')
            ->where('operator1',$preUser['name'])
            ->setField('operator1',$data[0]['name']);
        db('stuff_out_record')
            ->where('operator2',$preUser['name'])
            ->setField('operator2',$data[0]['name']);

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
        //查找其他表中是否有该生产商，若有则不能删除
        $user = db('user')->where('id',$id)->find();
        $tableList = ['stuff_in_record','staff'];
        foreach ($tableList as $table){
            $res = db($table)->where('operator',$user['name'])->find();
            if($res) return json(['state'=>'warning','message'=>'该管理员不能删除，因为在其它表中还存在该管理员']);
        }
        $res = db('stuff_leave_record')->where('receive_operator',$user['name'])->whereOr('send_operator',$user['name'])->find();
        if($res) return json(['state'=>'warning','message'=>'该管理员不能删除，因为在其它表中还存在该管理员']);
        $res = db('stuff_out_record')->where('operator1',$user['name'])->whereOr('operator2',$user['name'])->find();
        if($res) return json(['state'=>'warning','message'=>'该管理员不能删除，因为在其它表中还存在该管理员']);

        //删除Auth表中的数据
        $result = Manage::delete(new \app\index\model\Auth(),$id);
        if($result['state']!='success')
            return '{"state":"'.$result['state'].'","message":"管理员权限'.$result['message'].'"}';
        //删除user表数据
        return json(Manage::delete($this->model,$id));
    }
}