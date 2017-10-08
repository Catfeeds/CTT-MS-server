<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 12:08
 */

namespace app\index\validate;
use think\Validate;

class User extends Validate
{
    protected $rule=[
        'username'=>['require','max'=>50],
        'name'=>['require','max'=>20],
        'sex'=>['require','max'=>5],
        'password'=>['require','max'=>200],
        'idcard'=>['require','max'=>20],
        'area'=>['require','max'=>50],
        'phone'=>['max'=>20],
        'qq'=>['max'=>20],
        'email'=>['max'=>20,'email'],
        'address'=>['max'=>50],
    ];
    protected $message = [
        'username.max'=>'员工编号不能超过20个字符',
        'name.max'=>'姓名不能超过20个字符',
        'sex.max'=>'性别不能超过5个字符',
        'password.max'=>'密码过长！',
        'idcard.max'=>'身份证号不能超过20个字符',
        'area.max'=>'归属地区不能超过50个字符',
        'phone.max'=>'手机号码不能超过20个字符',
        'qq.max'=>'qq不能超过20个字符',
        'email.max'=>'邮箱不能超过50个字符',
        'address.max'=>'地址不能超过50个字符',

        'username.require'=>'员工编号不能为空',
        'name.require'=>'姓名不能为空',
        'password.require'=>'密码不能为空',
        'sex.require'=>'性别不能为空',
        'idcard.require'=>'身份证号不能为空',
        'area.require'=>'归属地区不能为空',

        'email.email'=>'邮箱格式错误'
    ];
}