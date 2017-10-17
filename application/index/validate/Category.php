<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 12:08
 */

namespace app\index\validate;
use think\Validate;

class Category extends Validate
{
    protected $rule=[
        'category_name'=>['require','max'=>50],
        'stuff_source'=>['require','max'=>50],
    ];
    protected $message = [
        'category_name.max'=>'材料大类不能超过50个字符',
        'stuff_source.max'=>'材料来源不能超过50个字符',

        'category_name.require'=>'材料大类不能为空',
        'stuff_source.require'=>'材料来源不能为空',
    ];
}