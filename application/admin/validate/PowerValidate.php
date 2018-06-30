<?php
namespace app\admin\validate;

use think\Validate;
class PowerValidate extends Validate
{
    protected $rule = [
        ['name', 'require'],
        ['controller', 'require'],
        ['action', 'require'],
        ['pid', 'require'],
    ];
    protected $message  =   [
        'name.require'  => '权限名称不能为空',
        'controller.require'  => '控制器不能为空',
        'action.require'  => '方法不能为空',
        'pid.require'   => '所属父级不能为空',
    ];
}