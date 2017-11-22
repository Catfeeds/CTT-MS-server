<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 12:08
 */

namespace app\index\validate;
use think\Validate;

class StuffOutRecord extends Validate
{
    protected $rule=[
        'inventory_id'=>['require','integer','min'=>1],
        'out_quantity'=>['require','integer','>=:1'],
        'odd_quantity'=>['require','integer','>=:0'],
        'storehouse'=>['require','max'=>20],
        'staff'=>['require','max'=>20],
        'operator1'=>['max'=>20],
        'operator2'=>['max'=>20],
        'is_out'=>['integer'],
        'apply_date'=>['require','date'],
        'out_date'=>['date'],
    ];
    protected $message = [
        'storehouse.max'=>'仓库名不能超过20个字符',
        'staff.max'=>'装维姓名不能超过20个字符',
        'operator1.max'=>'管理员不能超过20个字符',
        'operator2.max'=>'材料员不能超过20个字符',

        'inventory_id.require'=>'库存id不能为空',
        'out_quantity.require'=>'材料数量不能为空',
        'odd_quantity.require'=>'剩余数量不能为空',
        'storehouse.require'=>'材料仓库名称不能为空',
        'staff.require'=>'申请人不能为空',
        'apply_date.require'=>'申请日期不能为空',

        'apply_date.date'=>'申请日期格式错误',
        'out_date.date'=>'发放日期格式错误',
    ];
}