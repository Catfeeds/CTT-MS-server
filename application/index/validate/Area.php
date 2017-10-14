<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 12:08
 */

namespace app\index\validate;
use think\Validate;

class Area extends Validate
{
    protected $rule=[
        'province'=>['require','max'=>20],
        'city'=>['require','max'=>20],
        'district'=>['require','max'=>20]
    ];
    protected $message = [
        'province.max'=>'省名不能超过20个字符',
        'city.max'=>'市名不能超过20个字符',
        'district.max'=>'区县名不能超过20个字符',

        'province.require'=>'省名不能为空',
        'city.require'=>'市名不能为空',
        'district.require'=>'区县名不能为空',
    ];
}