<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 12:08
 */

namespace app\index\validate;
use think\Validate;

class Team extends Validate
{
    protected $rule=[
        'name'=>['require','max'=>20],
        'area'=>['require','max'=>50],
    ];
    protected $message = [
        'name.max'=>'班组名称不能超过20个字符',
        'area.max'=>'归属地区不能超过50个字符',

        'name.require'=>'班组名称不能为空',
        'area.require'=>'归属地区不能为空',
    ];
}