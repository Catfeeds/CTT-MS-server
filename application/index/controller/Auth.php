<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/9/26
 * Time: 12:51
 */

namespace app\index\controller;
use think\Controller;
use think\Session;
use think\Cookie;
use think\Request;

class Auth extends Controller
{
    //初始化方法，允许ajax跨域
    public function _initialize(){
        //允许ajax跨域
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Origin:http://10.2.130.195:8000');
    }

    //登录，只允许post请求
    public function login($username,$password){

        if(!input('?username')||!input('?password')) return json(['state'=>'error','message'=>'帐号或密码不存在']);

        //检测帐号和密码
        $password = sha1(md5($password));
        $user = db('user')->where('username',$username)->find();
        if(!$user) return json(['state'=>'error','message'=>'帐号不存在']);
        if($user['password']!=$password) return json(['state'=>'error','message'=>'密码错误']);

        //将当前ip写入session，每次操作时再检测是否为本ip
        $ip = get_proxy_ip();
        Session::set($username,$ip);

        //生成cookie,并将本次登陆的cookieUsername存入数据库
        $cookieUsername = md5($username.time());
        Cookie::set('username',$cookieUsername);
        db('user')->where('username',$username)->setField('cookie_username',$cookieUsername);

        //记录登录时间，更新登出时间
        $curDateTime = date('Y-m-d H:i:s');
        db('user')->where('username',$username)->setField('last_login_time',$curDateTime);
        db('user')->where('username',$username)->setField('last_logout_time',$curDateTime);

        //登录成功，将cookieUsername的值返回前端
        return json(['state'=>'success','message'=>$cookieUsername]);
    }

    //退出登录
    public function logout(){
        //从cookie中或者请求参数中获取cookieUsername
        $cookieUsername = cookie('?username')?cookie('username'):input('cookie');

        //更新最后注销时间
        db('user')->where('cookie_username',$cookieUsername)->setField('last_logout_time',date('Y-m-d H:i:s'));

        //清空数据库中对应的cookie_username项
        db('user')->where('cookie_username',$cookieUsername)->setField('cookie_username',null);

        //删除session和cookie
        Session::clear();
        Cookie::set('username',null);

        return json(['state'=>'success','message'=>'注销成功']);

    }

    //根据cookie和session检测用户权限，只允许post请求
    public function checkAuth(){
        //检测cookie是否存在
        if(!cookie('?username') && input('cookie')=='undefined')
            return json(['state'=>'error','message'=>'请先登录']);

        //检测username是否正确
        $user = getUser();
        if(!$user)
            return json(['state'=>'error','message'=>'该帐号已在其他地点登录']);

        //对比本机ip和session里的ip，若不同，则说明已在异地登录
        //前后端分离，登录时生成的session没法传到收不到session
//        if(Session::get($user['username'])!=get_proxy_ip())
//            return json(['state'=>'error','message'=>'该帐号已在其他地点登录']);

        //认证成功，返回权限json列表
        $auth = \app\index\model\Auth::get(1);
        return json(['state'=>'success','username'=>$user['username']]+$auth->hidden(['id'])->toArray());
    }
}