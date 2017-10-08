<?php
/**
 * Created by PhpStorm.
 * User: Horol
 * Date: 2017/10/1
 * Time: 12:08
 */

namespace app\index\validate;
use think\Validate;

class Auth extends Validate
{
    protected $rule=[
        'stuff_in'=>'boolean',
        'stuff_out'=>'boolean',
        'stuff_back'=>'boolean',
        'stuff_leave'=>'boolean',
        'stuff_use'=>'boolean',
        'stuff_count'=>'boolean',
        'stuff_inventory'=>'boolean',
        'tool_in'=>'boolean',
        'tool_out'=>'boolean',
        'tool_back'=>'boolean',
        'tool_leave'=>'boolean',
        'tool_count'=>'boolean',
        'tool_info_consummate'=>'boolean',
        'safty_in'=>'boolean',
        'safty_out'=>'boolean',
        'safty_back'=>'boolean',
        'safty_count'=>'boolean',
        'safty_infoconsummate'=>'boolean',
        'staff_manage'=>'boolean',
        'user_manage'=>'boolean',
    ];
}