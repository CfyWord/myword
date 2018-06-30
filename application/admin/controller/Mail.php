<?php
namespace app\admin\controller;

class Mail extends Base
{
    //邮件设置
    public function index()
    {
        if(request()->isPost()){
            $param = input('param.');
            $result = db('mail')->where('id', 1)->update($param);
            if(false !== $result){
                return json(['code'=>1,'msg'=>'编辑成功','url'=>url('mail/index')]);
            }   
        }
        $list = db('mail')->where('id',1)->find();
        $this->assign('list',$list);
        return $this->fetch();
    }
}