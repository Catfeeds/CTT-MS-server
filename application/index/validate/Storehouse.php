<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 12:08
 */

namespace app\index\validate;
use think\Validate;

class Storehouse extends Validate
{
    protected $rule=[
        'name'=>['require','max'=>20],
        'supervisor'=>['max'=>50],
        'store_address'=>['max'=>50],
        'area'=>['require','max'=>50],
    ];
    protected $message = [
        'name.max'=>'仓库名称不能超过20个字符',
        'supervisor.max'=>'负责人不能超过50个字符',
        'store_address.max'=>'仓库地址不能超过50个字符',
        'area.max'=>'归属地区不能超过50个字符',

        'name.require'=>'仓库名称不能为空',
        'area.require'=>'归属地区不能为空',
    ];
}