<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/9/29
 * Time: 21:40
 */

namespace app\index\model;
use think\Model;

class User extends Model
{
    //密码新增、更改时自动加密
    protected $auto = ['password'];
    public function setPasswordAttr($value){
        return sha1(md5($value));
    }
}