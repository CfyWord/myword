<?php
namespace app\admin\controller;

class System extends Base
{
	public function index()
    {
    	if(request()->isPost()){
    		$param = input('param.');
    		unset($param['file']);
    		$result = db('system')->where('id', 1)->update($param);
			if(false !== $result){
	            return json(['code'=>1,'msg'=>'编辑成功','url'=>url('system/index')]);
	        }	
    	}
    	$list = db('system')->where('id',1)->find();
    	$this->assign('list',$list);
    	return $this->fetch();
    }

    public function uploads()
    {
    	// $address = "1945733372@qq.com";
    	// $title = "您好！这是我的邮件标题。";
    	// $content = "您好！这是我的邮件内容。";
    	// SendMail($address);
    	// SendSms('13007814369','123456789');
    	return $this->fetch();
    }
}