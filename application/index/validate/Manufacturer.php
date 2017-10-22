<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 12:08
 */

namespace app\index\validate;
use think\Validate;

class Manufacturer extends Validate
{
    protected $rule=[
        'manufacturer'=>['require','max'=>20],
    ];
    protected $message = [
        'manufacturer.max'=>'厂商名不能超过20个字符',

        'manufacturer.require'=>'厂商名不能为空',
    ];
}