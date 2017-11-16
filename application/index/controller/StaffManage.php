<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 10:20
 */

namespace app\index\controller;
use think\Request;

class StaffManage extends Base
{
    //检测该用户是否有装维管理权限
    public function __construct()
    {
        parent::__construct();
        //查询$authList中是否有该操作的权限
        if($this->authList->staff_manage == 0){
            die(json_encode(['state'=>'warning','message'=>'没有装维管理权限'],JSON_UNESCAPED_UNICODE));
        }

        //尝试实例化Staff的模型类和验证器类，并且赋值给$model和$validate
        //若这两个类不存在，则抛出异常，返回错误信息
        try {
            $this->model = new \app\index\model\Staff();
            $this->validate = new \app\index\validate\Staff();
        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    //添加装维人员
    public function add(){
        //获取所有的请求变量并验证
        $data = Request::instance()->param();
        $result = $this->validate($data,'Staff');
        if(true !== $result){
            // 验证失败 输出错误信息
            return json(['state'=>'warning','message'=>$result]);
        }

        //查询装维人员是否已经存在
        $result = db('staff')->where('name',$data['name'])->select();
        if($result) return '{"state":"warning","message":"装维人员姓名姓名已存在"}';

        //查询班组和对应的地区是否合法
        if(!empty($data['team'])){
            $result0 = db('team')->where('name',$data['team'])->where('area',$data['area'])->select();
            if(!$result0)
                return '{"state":"warning","message":"所属班组或地址有误"}';
        }

        // 获取表单上传文件
        $files = request()->file('img');
        $i=0;
        foreach($files as $file){
            switch ($i){//判断图片相应的键名
                case 0:
                    $key = 'per_pic'; break;
                case 1:
                    $key = 'idcard_front_pic';break;
                case 2:
                    $key = 'idcard_back_pic';break;
                default: $key = '';
            }
            $i++;
            // 移动到框架应用根目录/public/staff/ 目录下
            $path = ROOT_PATH.'public'.DS.'staff/'.$key.'/';
            $info = $file->validate(['ext'=>'jpg,png,gif,jpeg,bmp'])->move($path);
            if($info){
                //将路径+文件名存入$data数组
                $data[$key]=dirname($_SERVER['SCRIPT_NAME']).DS.'public'.DS.'staff'.DS.$key.DS.$info->getSaveName();
            }else{
                // 上传失败获取错误信息
                return json(['state'=>'warning','message'=>$file->getError()]);
            }
        }
        //通过cookie来找到当前管理员姓名
        $operator = db('user')->where('cookie_username',$this->cookieUsername)->value('name');
        $data['operator'] = $operator;
        //将手机号码加密后存入password字段
        $data['password'] = sha1(md5($data['phone']));
        //使用Manage类的add静态方法验证、添加数据
        return json(Manage::add($this->model,$this->validate,$data));
    }

    //查找的装维人员
    public function check(){
        //有参数的情况
        $query = isset(Request::instance()->post(false)['query'])?Request::instance()->post(false)['query']:null;
        if($query){
            //示例json
//            $json = '{
//                    "pageinfo":{"curpage":2,"pageinate":3},
//                    "order":"id desc",
//                    "condition":{
//                                "where":["on_guard","是"],
//                                "like":["area|name","%四川%"],
//                                "between":["employment_date",["2017-10-01","2017-10-08"]]
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
        else  //没有参数的情况
            $staff = Manage::check($this->model);
        return json($staff);
    }

    //修改装维人员信息
    public function change(){
        //获取所有的请求变量
        $data = Request::instance()->param();

        //查询装维人员是否已经存在
        $result = db('staff')->where('id','neq',$data['id'])->where('name',$data['name'])->select();
        if($result) return '{"state":"warning","message":"装维人员姓名姓名已存在"}';

        //查询班组和对应的地区是否合法
        if(!empty($data['team'])){
            $result0 = db('team')->where('name',$data['team'])->where('area',$data['area'])->select();
            if(!$result0)
                return '{"state":"warning","message":"所属班组或地址有误"}';
        }

        // 获取表单上传文件
        $file1 = request()->file('per_pic');
        if($file1){
            // 移动到框架应用根目录/public/staff/ 目录下
            $path = ROOT_PATH.'public' . DS . 'staff/per_pic/';
            $info = $file1->validate(['ext'=>'jpg,png,gif,jpeg,bmp']) ->move($path);
            if($info){
                //将路径+文件名存入$data数组
                $data['per_pic']=dirname($_SERVER['SCRIPT_NAME']).DS.'public'.DS.'staff'.DS.'per_pic'.DS.$info->getSaveName();
            }else{
                // 上传失败获取错误信息
                return json(['state'=>'warning','message'=>$file1->getError()]);
            }
        }
        $file2 = request()->file('idcard_front_pic');
        if($file2){
            // 移动到框架应用根目录/public/staff/ 目录下
            $path = ROOT_PATH.'public'. DS . 'staff/idcard_front_pic/';
            $info = $file2->validate(['ext'=>'jpg,png,gif,jpeg,bmp']) ->move($path);
            if($info){
                //将路径+文件名存入$data数组
                $data['idcard_front_pic']=dirname($_SERVER['SCRIPT_NAME']).DS.'public'.DS.'staff'.DS.'idcard_front_pic'.DS.$info->getSaveName();

            }else{
                // 上传失败获取错误信息
                return json(['state'=>'warning','message'=>$file2->getError()]);
            }
        }
        $file3 = request()->file('idcard_back_pic');
        if($file3){
            // 移动到框架应用根目录/public/staff/ 目录下
            $path = ROOT_PATH. 'public' . DS . 'staff/idcard_back_pic/';
            $info = $file3->validate(['ext'=>'jpg,png,gif,jpeg,bmp']) ->move($path);
            if($info){
                //将路径+文件名存入$data数组
                $data['idcard_back_pic']=dirname($_SERVER['SCRIPT_NAME']).DS.'public'.DS.'staff'.DS.'idcard_back_pic'.DS.$info->getSaveName();
            }else{
                // 上传失败获取错误信息
                return json(['state'=>'warning','message'=>$file3->getError()]);
            }
        }
        //通过cookie来找到当前管理员姓名
        $operator = db('user')->where('cookie_username',$this->cookieUsername)->value('name');
        $data['operator'] = $operator;

        //修改其他表中存放的装维姓名
        $tableList = ['stuff_out_record'];
        $preUser = db('staff')->where('id',$data['id'])->find();
        foreach ($tableList as $table){
            db($table)
                ->where('staff',$preUser['name'])
                ->setField('staff',$data['name']);
        }

        //使用Manage类的change静态方法验证、修改数据
        return json(Manage::change($this->model,$this->validate,$data));
    }

    //删除装维人员
    public function delete(){
        $id = input('id');
        //查找其他表中是否有该装维人员，若有则不能删除
        $user = db('staff')->where('id',$id)->find();
        $tableList = ['stuff_out_record'];
        foreach ($tableList as $table){
            $res = db($table)->where('staff',$user['name'])->find();
            if($res) return json(['state'=>'warning','message'=>'该装维人员不能删除，因为在其它表中还存在该装维姓名']);
        }
        return json(Manage::delete($this->model,$id));
    }
}