<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 12:08
 */

namespace app\index\validate;
use think\Validate;

class StuffLeaveRecord extends Validate
{
    protected $rule=[
        'inventory_id'=>['require','integer','min'=>1],
        'leave_quantity'=>['require','number','min'=>1],
        'send_storehouse'=>['require','max'=>20],
        'leave_storehouse'=>['require','max'=>20],
        'send_operator'=>['require','max'=>20],
        'receive_operator'=>['max'=>20],
        'is_receive'=>['boolean'],
        'send_date'=>['require','date'],
        'receive_date'=>['date'],
    ];
    protected $message = [
        'send_storehouse.max'=>'调离仓库名不能超过20个字符',
        'receive_storehouse.max'=>'接收仓库名不能超过20个字符',
        'send_operator.max'=>'调拨经办人不能超过20个字符',
        'receive_operator.max'=>'接收经办人不能超过20个字符',

        'inventory_id.require'=>'库存id不能为空',
        'leave_quantity.require'=>'调拨数量不能为空',
        'send_storehouse.require'=>'调离仓库名称不能为空',
        'leave_storehouse.require'=>'接收仓库名称不能为空',
        'send_operator.require'=>'调拨经办人不能为空',
        'send_date.require'=>'调拨日期不能为空',

        'send_date.date'=>'调拨日期格式错误',
        'receive_date.date'=>'接收日期格式错误',
    ];
}