<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 22:53
 */

namespace app\index\controller;
use think\Controller;
use think\Request;

//除了Auth类，其他控制器类都应继承Base类，用于检测根据cookie返回权限列表
class Base extends Controller
{
    //Auth模型对象，用于检测权限
    protected $authList;

    //Model模型对象，派生类应该实例化相应的Model类并赋值给$model
    protected $model;

    //Validate验证器对象，派生类应该实例化相应的Validate类并赋值给$validate
    protected $validate;

    //构造方法通过检测cookie的值，判断登录是否非法；若合法，将赋值auth对象给$authList
    public function __construct(){
        parent::__construct();
        //允许ajax跨域
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Origin:http://10.2.130.195:8000');


        //检测cookie是否存在
        if(!cookie('?username')&&!input('?cookie')){
            die(json_encode(['state'=>'error','message'=>'请先登录'],JSON_UNESCAPED_UNICODE));
        }

        //检测username是否正确
        $cookieUsername = cookie('?username')?cookie('username'):input('cookie');
        $user = db('user')->where('cookie_username',$cookieUsername)->find();
        if(!$user){
            die(json_encode(['state'=>'error','message'=>'该帐号已在其他地点登录'],JSON_UNESCAPED_UNICODE));
        }

        //找到该用户对应的model的Auth对象,赋值给$authList
        $auth =  \app\index\model\Auth::get($user['id']);
        $this->authList = $auth;
    }
}