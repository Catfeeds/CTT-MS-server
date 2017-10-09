<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 12:08
 */

namespace app\index\validate;
use think\Validate;

class Staff extends Validate
{
    protected $rule=[
        'name'=>['require','max'=>20],
        'sex'=>['require','max'=>5],
        'on_guard'=>['require','max'=>5],
        'idcard'=>['require','max'=>20],
        'area'=>['require','max'=>50],
        'phone'=>['max'=>20],
        'qq'=>['max'=>20],
        'sec_linkman'=>['max'=>20],
        'sec_phone'=>['max'=>20],
        'address'=>['max'=>50],
        'education'=>['max'=>20],
        'school'=>['max'=>50],
        'operator'=>['require','max'=>50],
        'employment_date'=>['require','date'],
        'per_pic'=>['max'=>300],
        'idcard_front_pic'=>['max'=>300],
        'idcard_back_pic'=>['max'=>300],
    ];
    protected $message = [
        'name.max'=>'姓名不能超过20个字符',
        'sex.max'=>'性别不能超过5个字符',
        'on_guard.max'=>'是否在职不能超过5个字符',
        'idcard.max'=>'身份证号不能超过20个字符',
        'area.max'=>'归属地区不能超过50个字符',
        'phone.max'=>'手机号码不能超过20个字符',
        'qq.max'=>'qq不能超过20个字符',
        'sec_linkman.max'=>'第二联系人不能超过20个字符',
        'sec_phone.max'=>'第二联系人手机不能超过20个字符',
        'address.max'=>'地址不能超过50个字符',
        'education.max'=>'学历不能超过20个字符',
        'school.max'=>'毕业学校不能超过50个字符',
        'operator.max'=>'经办人员不能超过50个字符',
        'per_pic.max'=>'个人照片路径不能超过300个字符',
        'idcard_front_pic.max'=>'身份证正面照不能超过300个字符',
        'idcard_back_pic.max'=>'身份证背面照不能超过300个字符',

        'name.require'=>'装维人员姓名不能为空',
        'sex.require'=>'性别不能为空',
        'on_guard.require'=>'是否在岗不能为空',
        'idcard.require'=>'身份证号不能为空',
        'area.require'=>'归属地区不能为空',
        'operator.require'=>'经办人员不能为空',
        'employment_date.require'=>'入职时间不能为空',

        'employment_date.date'=>'入职时间格式错误',
    ];
}