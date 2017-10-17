<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 12:08
 */

namespace app\index\validate;
use think\Validate;

class Stuff extends Validate
{
    protected $rule=[
        'category_name'=>['require','max'=>50],
        'stuff_name'=>['require','max'=>50],
        'unit'=>['require','max'=>20],
    ];
    protected $message = [
        'category_name.max'=>'材料大类不能超过50个字符',
        'stuff_name.max'=>'材料名称不能超过50个字符',
        'unit.max'=>'材料单位单位不能超过20个字符',

        'category_name.require'=>'材料大类不能为空',
        'stuff_name.require'=>'材料名称不能为空',
        'unit.require'=>'材料单位名称不能为空',
    ];
}