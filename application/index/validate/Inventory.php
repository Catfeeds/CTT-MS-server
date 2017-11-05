<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 12:08
 */

namespace app\index\validate;
use think\Validate;

class Inventory extends Validate
{
    protected $rule=[
        'stuff_in_record_id'=>['require','integer','egt:1'],
        'stuff_id'=>['require','integer','min'=>1],
        'manufacturer'=>['require','max'=>20],
        'type'=>['require','max'=>20],
        'quantity'=>['require','integer','egt:1'],
        'storehouse'=>['require','max'=>20],
        'enabled'=>['boolean']
    ];
    protected $message = [
        'manufacturer.max'=>'生产商不能超过20个字符',
        'type.max'=>'型号不能超过20个字符',
        'storehouse.max'=>'仓库名不能超过20个字符',
        'quantity.egt:1'=>'入库数量必须大于等于1',
        'quantity.integer'=>'入库数量必须为整数',

        'stuff_id.require'=>'材料名称id不能为空',
        'manufacturer.require'=>'生产商不能为空',
        'type.require'=>'型号不能为空',
        'quantity.require'=>'入库数量不能为空',
        'storehouse.require'=>'仓库名称不能为空',
    ];
}