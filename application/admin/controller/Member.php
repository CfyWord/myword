<?php
namespace app\admin\controller;

use app\common\model\MemberModel;
use app\common\model\GroupModel;

class Member extends Base
{
	//会员列表
    public function index()
    {
    	if(request()->isAjax()){
     		$page = input('param.page');
            $limit = input('param.limit');
            $MemberModel = new MemberModel();
            $list = $MemberModel->alias('m')
                  ->join('__MEMBER_GROUP__ g','m.group_id = g.id','left')
                  ->field('m.*,g.group_name,g.level')
                  ->order('m.id asc')
                  ->paginate($limit)->toArray();

            return json(['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total']]);
    	}
    	
        return $this->fetch();
    }

    //添加会员
    public function add()
    {
    	if(request()->isPost()){
    		$param = input('param.');
            $param['nickname'] = @$param['nickname']?$param['nickname']:$param['username'];
            $MemberModel = new MemberModel();
    		$validate = $this->validate($param,'MemberValidate.admin');
    		if(true !== $validate){
			   	return json(['code'=>2,'msg'=>$validate,'url'=>"javascript:;"]);
			}else{
				$salt = salt(8);
				$param['password'] = md5(md5(input('param.password')).$salt);
				$param['pwdkey'] = $salt;
				$result = $MemberModel->allowField(true)->data($param)->save();
				if(false !== $result){
					return json(['code'=>1,'msg'=>'添加成功','url'=>url('Member/index')]);
				}
			}
    	}
        $GroupModel = new GroupModel();
    	$group = $GroupModel->field('id,group_name')->select();
    	$this->assign('group',$group);
    	return $this->fetch();
    }

    //修改会员
    public function edit()
    {
    	$MemberModel = new MemberModel();
    	if(request()->isPost()){
    		$param = input('param.');
    		$validate = $this->validate($param,'MemberValidate.edit');
    		if(true !== $validate){
			   	return json(['code'=>2,'msg'=>$validate,'url'=>"javascript:;"]);
			}else{
				if($param['password'] == ''){
		            unset($param['password']);
		            unset($param['repassword']);
		        }else{
		            $salt = salt(8);
		            $param['pwdkey'] = $salt;	
		            $param['password'] = md5(md5(input('param.password')).$salt);
		        }
				$result = $MemberModel->allowField(true)->save($param, ['id' => $param['id']]);
				if(false !== $result){
	            	return json(['code'=>1,'msg'=>'编辑成功','url'=>url('Member/index')]);
	            }
			}
    	}else{
    		$id = input('param.id');
            $GroupModel = new GroupModel();
	    	$group = $GroupModel->field('id,group_name')->select();
	    	$list = $MemberModel->where('id',$id)->find();
	    	$this->assign('group',$group);
	    	$this->assign('list',$list);
	    	return $this->fetch();
    	}	
    }

    //设置状态status
    public function status()
    {
    	if(request()->isPost()){
    		$param = input('param.');
            $MemberModel = new MemberModel();
            $result = $MemberModel->where('id',$param['id'])->update(['status' =>$param['status']]);
            if(false !== $result){
            	return json(['code'=>1,'msg'=>'设置成功','url'=>'javascript:;']);
            }
    	}

    }

    //删除会员
    public function del()
    {
    	if(request()->isPost()){
    		$id = trim(input('param.id'),',');
            $MemberModel = new MemberModel();
            $result = $MemberModel->where('id','in', $id)->delete();
            if($result){
            	return json(['code'=>1,'msg'=>'删除成功','url'=>url('Member/index')]);
            }else{
            	return json(['code'=>2,'msg'=>'删除失败','url'=>url('Member/index')]);
            }
    	}

    }
}
