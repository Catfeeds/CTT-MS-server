<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/22
 * Time: 13:33
 */

namespace app\index\controller;


class Changepassword extends Base
{
    public function index(){
        //echo input('old_password').input('new_password');
        $prePassword = input('old_password');
        if(input('new_password')=='' || input('new_password')==null)
            return json(['state'=>'warning','message'=>'密码不能为空']);
        $newPassword = sha1(md5(input('new_password')));
        $user = getUser();
        $password = $user['password'];
        if(sha1(md5($prePassword))!=$password) return json(['state'=>'warning','message'=>'原密码错误']);
        $cookieUsername = cookie('?username')?cookie('username'):input('cookie');
        db('user')->where('cookie_username',$cookieUsername)->setField('password',$newPassword);
        return json(['state'=>'success','message'=>'修改成功']);
    }
}