<?php
namespace app\admin\controller;

use \think\Controller;
use org\Verify;
class Login extends controller
{
	// 登录页面
    public function index()
    {
        return $this->fetch();
    }

    // 登录操作
    public function doLogin()
    {   
        $username = input("post.username");
        $password = input("post.password");
        $captcha = input("post.code");
        if(!$username||!$password||!$captcha){
        	return json(['code'=>2,'msg'=>'用户名、密码、验证码不能为空','url'=>'javascript:;']);
        }
        if(!captcha_check($captcha)){
		    return json(['code'=>2,'msg'=>'验证码错误','url'=>'javascript:;']);
		};
        $hasUser =db('user')->where('username', $username)->find();
        if(empty($hasUser)){
            return json(['code'=>2,'msg'=>'用户不存在','url'=>'javascript:;']);
        }
        if(md5(md5($password).$hasUser['pwdkey'])!= $hasUser['password']){
            return json(['code'=>2,'msg'=>'密码错误','url'=>'javascript:;']);
        }
        if(1 != $hasUser['status']){
            return json(['code'=>2,'msg'=>'该账号被禁用','url'=>'javascript:;']);
        }
        session('wx_username', $username);
        session('wx_user_id', $hasUser['user_id']);
        // 更新用户状态
        $param = [
            'login_num' => $hasUser['login_num'] + 1,
            'last_login_ip' => request()->ip(),
            'last_login_time' => time()
        ];
        db('user')->where('user_id',$hasUser['user_id'])->update($param);
        return json(['code'=>1,'msg'=>'登录成功','url'=>'/public/admin/index/index']);
    }

    //退出登陆
    public function logout(){
        session(null);
        $this->redirect('login/index');
    }
}
