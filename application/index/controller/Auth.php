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

class Auth extends Controller
{
    //初始化方法，允许ajax跨域
    public function _initialize(){
        header('Access-Control-Allow-Origin:*');
    }

    //登录，只允许post请求
    public function login($username,$password){
        if(!input('?username')||!input('?password')) return json(['state'=>'error','message'=>'帐号或密码不存在']);

        //检测帐号和密码
        $password = sha1(md5($password));
        $user = db('user')->where('username',$username)->find();
        if(!$user) $this->error('用户名不存在');
        if($user['password']!=$password) $this->error('密码错误');

        //将当前ip写入session，每次操作时再检测是否为本ip
        $ip = get_proxy_ip();
        Session::set($username,$ip);

        //生成cookie,并将本次登陆的cookieUsername存入数据库
        $cookieUsername = md5($username.time());
        Cookie::set('username',$cookieUsername);
        db('user')->where('username',$username)->setField('cookie_username',$cookieUsername);

        //记录登录时间
        db('user')->where('username',$username)->setField('last_login_time',date('Y-m-d H:i:s'));

        //跳转主页
        return '<script type="text/javascript">window.location.href="/CTT-MS/main.html";</script>';
    }

    //退出登录
    public function logout(){
        //ajax请求时，说明是切换、关闭、刷新窗口时js发送的请求，只执行更新最后注销时间操作，而不修改cookie等，防止不能操作
        //若不是ajax请求时，说明是点击注销的请求，执行所有注销操作
        $cookieUsername = cookie('username');
        //更新最后注销时间
        db('user')->where('cookie_username',$cookieUsername)->setField('last_logout_time',date('Y-m-d H:i:s'));
        if (!request()->isAjax()){
            //清空数据库中对应的cookie_username项
            db('user')->where('cookie_username',$cookieUsername)->setField('cookie_username',null);

            //删除session和cookie
            Session::clear();
            Cookie::set('username',null);

            return '<script type="text/javascript">window.location.href="/CTT-MS/login.html";</script>';
        }
    }

    //根据cookie和session检测用户权限，只允许post请求
    public function checkAuth(){
        //检测cookie是否存在
        if(!cookie('?username'))
            return json(['state'=>'error','message'=>'请先登录']);

        //检测username是否正确
        $cookieUsername = cookie('username');
        $user = db('user')->where('cookie_username',$cookieUsername)->find();
        if(!$user)
            return json(['state'=>'error','message'=>'该帐号已在其他地点登录']);

        //对比本机ip和session里的ip，若不同，则说明已在异地登录
        if(Session::get($user['username'])!=get_proxy_ip())
            return json(['state'=>'error','message'=>'该帐号已在其他地点登录']);

        //认证成功，返回权限json列表
        $auth = \app\index\model\Auth::get(1);
        return json(['state'=>'success','username'=>$user['username']]+$auth->hidden(['id'])->toArray());
    }
}