<?php
namespace app\common\validate;

use think\Validate;
class MemberValidate extends Validate
{
    protected $rule = [
        ['username', 'require|alphaNum|length:6,16|unique:member'],
        ['password', 'require|alphaNum|length:6,16|confirm:repassword'],
        ['group_id', 'require'],
    ];
    protected $message  =   [
        'username.require'  => '会员账号不能为空',
        'username.alphaNum' => '会员账号只能是字母和数字',
        'username.length'      => '会员账号字符在6~16',
        'username.unique'   => '会员账号已存在',
        'password.require'  => '密码不能为空', 
        'password.alphaNum' => '密码只能是字母和数字',
        'password.length'   => '密码字符在6~16位',
        'password.confirm'  => '两次密码不一样', 
        'group_id.require'   => '请选择会员组',   
    ];
    protected $scene = [
        'admin' =>["username",'password',"group_id"],
        'edit'  =>  ['username'=>'require|alphaNum|length:6,16|unique:member,username^id','password'=>'alphaNum|length:6,16|confirm:repassword','group_id'=>'require'],//username^id 验证会员账号唯一
    ];
}