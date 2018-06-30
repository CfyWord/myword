<?php
namespace app\admin\validate;

use think\Validate;
class UserValidate extends Validate
{
    protected $rule = [
        ['username', 'require|alphaNum|length:6,16|unique:user'],
        ['password', 'require|alphaNum|length:6,16|confirm:repassword'],
        ['role_id', 'require'],
    ];
    protected $message  =   [
        'username.require'  => '用户名不能为空',
        'username.alphaNum' => '用户名只能是字母和数字',
        'username.length'      => '用户名字符在6~16',
        'username.unique'   => '用户名已存在',
        'password.require'  => '密码不能为空', 
        'password.alphaNum' => '密码只能是字母和数字',
        'password.length'   => '密码字符在6~16',
        'password.confirm'  => '两次密码不一样', 
        'role_id.require'   => '请选择用户组',   
    ];
    protected $scene = [
        'edit'  =>  ['username'=>'require|alphaNum|length:5|unique:user,username^user_id','password'=>'alphaNum|length:6,16|confirm:repassword','role_id'=>'require'],//username^id 验证用户名唯一
    ];
}