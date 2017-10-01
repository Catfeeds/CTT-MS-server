<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 22:53
 */

namespace app\index\controller;
use think\Controller;

//除了Auth类，其他类都应继承Base类，用于检测根据cookie返回权限列表
class Base extends Controller
{
    //Auth模型对象，用于检测权限
    protected $authList;

    public function __construct(){
        parent::__construct();
        //允许ajax跨域
        header('Access-Control-Allow-Origin:*');

        //检测cookie是否存在
        if(!cookie('?username')){
            die(json_encode(['state'=>'error','message'=>'请先登录'],JSON_UNESCAPED_UNICODE));
        }


        //检测username是否正确
        $cookieUsername = cookie('username');
        $user = db('user')->where('cookie_username',$cookieUsername)->find();
        if(!$user){
            die(json_encode(['state'=>'error','message'=>'该帐号已在其他地点登录'],JSON_UNESCAPED_UNICODE));
        }

        //找到该用户对应的权限,赋值给$authList
        $auth =  \app\index\model\Auth::get($user['id']);
        $this->authList = $auth;
    }
}