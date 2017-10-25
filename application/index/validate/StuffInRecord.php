<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 12:08
 */

namespace app\index\validate;
use think\Validate;

class StuffInRecord extends Validate
{
    protected $rule=[
        'stuff_id'=>['require','integer','min'=>1],
        'manufacturer'=>['require','max'=>20],
        'type'=>['require','max'=>20],
        'quantity'=>['require','number','min'=>1],
        'storehouse'=>['require','max'=>20],
        'stuff_in_date'=>['require','date'],
        'operator'=>['require','max'=>20],
        'enabled'=>['boolean']
    ];
    protected $message = [
        'manufacturer.max'=>'生产商不能超过20个字符',
        'type.max'=>'型号不能超过20个字符',
        'storehouse.max'=>'仓库名不能超过20个字符',
        'operator.max'=>'仓库名不能超过20个字符',

        'stuff_id.require'=>'材料名称id不能为空',
        'manufacturer.require'=>'生产商不能为空',
        'type.require'=>'型号不能为空',
        'quantity.require'=>'入库数量不能为空',
        'storehouse.require'=>'仓库名称不能为空',
        'stuff_in_date.require'=>'入库时间不能为空',
        'operator.require'=>'经办人不能为空',

        'stuff_in_date.date'=>'入库时间格式错误',
    ];
}