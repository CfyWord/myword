<?php
namespace app\admin\controller;

class Sms extends Base
{
    //邮件设置
    public function index()
    {
        if(request()->isPost()){
            $param = input('param.');
            $result = db('aliyun_sms')->where('id', 1)->update($param);
            if(false !== $result){
                return json(['code'=>1,'msg'=>'编辑成功','url'=>url('sms/index')]);
            }   
        }
        $list = db('aliyun_sms')->where('id',1)->find();
        $this->assign('list',$list);
        return $this->fetch();
    }
}